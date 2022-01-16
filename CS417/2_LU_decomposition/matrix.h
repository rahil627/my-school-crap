class matrix
{
public:
    matrix(int N);
    void genx();
    void genb();
    void display();
    void display2();
    void solve();
    bool check();

private:
    int n;//size
    double **A;
    double *x;
    double *b;
    double *s;
    double *e;
    double norm;
    double **L;
    double **U;
    double *y;
};

matrix::matrix(int N)//initialize everything
{
    n=N;
    A = new double * [n];//LHS matrix
    b = new double [n];//RHS vector
    x = new double [n];//randomly generated solution
    s = new double [n];//solution vector derived from solviing the system
    e = new double [n];//error vector s-x
    L = new double * [n];//lower triangle
    U = new double * [n];//upper triangle
    y = new double [n];//U*x

    genx();

    //genA
    for (int i=0; i<n; i++){A[i]= new double [n];}
    int r;

    for(int i=0; i<n; i++)//don't need brackets?
        for(int j=0; j<n; j++)
        {
            A[i][j]=( (double) (rand()%10000) )/100.0;
            r=rand()%2;
            if(r==1){A[i][j]=A[i][j]*-1;}
        }
    /*
    //enforcing diagonal dominance
    double sum=0;
    for (int row=0; row<n; row++)
    {
        for(int col=0; col<n; col++)
        {
            if(row!=col) {sum= sum+abs(A[row][col]);}
        }
        A[row][row]=sum+100;
        sum=0;
    }
    */
    genb();//uses x & a to generate



    //initialize vectors
    for(int i=0; i<n; i++)
    {
        s[i]=0;
        e[i]=0;
        y[i]=0;
    }

    //initialize matricies
    for (int i=0; i<n; i++){L[i]= new double [n];}
    for (int i=0; i<n; i++){U[i]= new double [n];}

    for(int i=0; i<n; i++)//don't need brackets?
        for(int j=0; j<n; j++)
        {
            L[i][j]=0;
            U[i][j]=0;
        }

    //initialize norm
    norm=0;

    //hard-coded samples
    /*
    //3x3 example lecture 2
    A[0][0]=4;
    A[0][1]=-4;
    A[0][2]=8;

    A[1][0]=3;
    A[1][1]=-2;
    A[1][2]=9;

    A[2][0]=5;
    A[2][1]=-3;
    A[2][2]=23;
    */

    /*
    //problem #12 from P&P2 solution: s[0]=1, s[1]=-1, s[2]=2
    A[0][0]=2;
    A[0][1]=-1;
    A[0][2]=0;
    b[0]=3;

    A[1][0]=0;
    A[1][1]=4;
    A[1][2]=3;
    b[1]=2;

    A[2][0]=1;
    A[2][1]=0;
    A[2][2]=-1;
    b[2]=-1;
    */
}

void matrix::display()
{
    cout<<setw(n*10)<<"A"<<setw(10)<<"x"<<setw(10)<<"b"<<endl;
    for(int i=0; i<n; i++)
    {
        for(int j=0; j<n; j++){cout<<setw(10)<<A[i][j];}
        cout<<setw(10)<<x[i];
        cout<<setw(10)<<b[i];
        cout<<endl;
    }
    cout<<endl;
}

void matrix::display2()
{
    cout<<setw(n*10)<<"L"<<endl;
    for(int i=0; i<n; i++)
    {
        for(int j=0; j<n; j++){cout<<setw(10)<<L[i][j];}
        cout<<endl;
    }
    cout<<endl<<setw(n*10)<<"U"<<endl;
    for(int i=0; i<n; i++)
    {
        for(int j=0; j<n; j++){cout<<setw(10)<<U[i][j];}
        cout<<endl;
    }
    cout<<endl<<setw(10)<<"y"<<setw(10)<<"s (x)"<<setw(10)<<"e"<<endl;
    for(int i=0; i<n; i++)
    {
        cout<<setw(10)<<y[i];
        cout<<setw(10)<<s[i];
        cout<<setw(15)<<e[i];
        cout<<endl;
    }
    cout<<endl<<"norm: "<<norm<<endl;
}

void matrix::genx()
{
    int r;
     for(int row=0; row<n; row++)
     {
        x[row]=( (double) (rand()%10000) )/100.0;
        r=rand()%2;
        if(r==1){x[row]=x[row]*-1;}
     }
}

void matrix::genb()
{
    double temp = 0;
    for(int row=0; row<n; row++)
    {
        for(int col=0; col<n; col++)
        {
            temp=(A[row][col]*x[col])+temp;
        }
        b[row]=temp;
        temp=0.0;
    }
}

bool matrix::check()
{
    //check for 0's along the diagonal
    bool flag=false;
    for(int i=0; i<n; i++)
    {
        if(A[i][i]==0){flag=true;}
    }
    return flag;
}

void matrix::solve()
{
    //guarenteed portions

    //L's first column = A's first column
    for(int i=0; i<n; i++)
    {
        L[i][0]=A[i][0];
    }


    //U diagonal 1's
    for(int i=0; i<n; i++)
    {
        U[i][i]=1;
    }

    //everything was initilized to zero, so the guaranteed zeros are already zero

    //solve for the missing portions

    //vars
    int z=1;//incrementing variable
    double LHS=0;//left hand side except the variable being solved for and it's multiple
    double RHS=0;//right hand side
    double multiplier;//the multiplier of the variable

    //solve
    for(int i=0; i<n; i++)//big for loop
    {
            //U - solve for a row
            //cout<<"solving for a U row"<<endl;
            for(int c=z; c<n; c++)
            {
                //cout<<"entry: "<<c-1<<endl;

                //a single vector multiplication -> L[row i]*U[column c]
                for(int counter=0; counter<n; counter++)
                {
                    if(i==counter&&L[i][counter]!=0)
                    {//catch the variable being solved for and it's multiplier
                        multiplier=L[i][counter];
                        //cout<<"multiplier: "<<multiplier<<endl;
                    }
                    else
                    {
                        LHS=LHS + L[i][counter]*U[counter][c];
                        //cout<<"LHS set: "<<L[i][counter]*U[counter][c]<<endl;
                    }
                }
                RHS=A[i][c];
                RHS=RHS-LHS;
                if(multiplier==0){RHS=0;}//catch division by 0
                else{RHS=RHS/multiplier;}
                U[i][c]=RHS;

                LHS=0;
                //multiplier=0;
            }

            //reset vars
            multiplier=0;
            LHS=0;
            RHS=0;

            //L - solve for a column
            //cout<<"solving for a L row"<<endl;
            for(int r=z; r<n; r++)
            {
                //cout<<"entry: "<<r-1<<endl;
                //a single vector multiplication -> L[row i]*U[column c]
                for(int counter=0; counter<n; counter++)
                {
                    if(i+1==counter&&U[counter][i+1]!=0)
                    {//catch the variable being solved for and it's multiplier
                        multiplier=U[counter][i+1];
                        //cout<<"multiplier: "<<multiplier<<endl;
                    }
                    else
                    {
                        LHS=LHS + L[r][counter]*U[counter][i+1];
                        //cout<<"LHS set: "<<L[r][counter]*U[counter][i+1]<<endl;
                    }
                }

                RHS=A[r][i+1];
                RHS=RHS-LHS;
                if(multiplier==0){RHS=0;}//catch division by 0
                else{RHS=RHS/multiplier;}
                L[r][i+1]=RHS;

                //reset vars
                LHS=0;
                //multiplier=0;

            }
            z++;

            //reset vars
            multiplier=0;
            LHS=0;
            RHS=0;
    }

    //forward and backward substitutions
    double temp=0;

    //forward substitution
    //Ly=b
    y[0]=b[0]/L[0][0];
    for(int i=1; i<n; i++)
    {
       temp = b[i];
       for(int j=0;j<n;j++)
       {
           temp = temp - (L[i][j] * y[j]);//b-A*s for each variable
       }
       y[i] = temp/L[i][i];
    }

    //backward substitution
    //Ux=y
    s[n-1]=y[n-1]/U[n-1][n-1];
    for(int i=(n-2); i>=0; i--)
    {
       temp = y[i];
       for(int j=(i+1);j<n;j++)
       {
           temp -= (U[i][j] * s[j]);//b-A*s for each variable
       }
       s[i] = temp/U[i][i];
    }

    //compute the error vector (s-x)
    for(int i=0; i<n; i++)
    {
        e[i]=s[i]-x[i];
    }

    //compute the 2-norm
    double enorm=0;
    double snorm=0;
    for(int i=0; i<n; i++)
    {
        enorm=enorm+pow(e[i],2);
        snorm=snorm+pow(s[i],2);

    }
    sqrt(enorm);
    sqrt(snorm);
    if(snorm==0){norm=0;}//catch division by 0
    else{norm=enorm/snorm;}
}
