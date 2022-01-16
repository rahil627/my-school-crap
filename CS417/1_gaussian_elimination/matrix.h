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
};

matrix::matrix(int N)//initialize everything
{
    n=N;
    A = new double * [n];//LHS matrix
    b = new double [n];//RHS vector
    x = new double [n];//given randomly generated solution vector
    s = new double [n];//solution vector derived from solving the system using only A and b
    e = new double [n];//error vector s-x

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
    genb();//A*x

    //inititialize s
    for(int i=0; i<n; i++)
    {
        s[i]=0;
    }

    //initialize e
    for(int i=0; i<n; i++)
    {
        e[i]=0;
    }
    //initialize norm
    norm=0;



    /*
    //sample problem used in class - isn't diagonally dominant | solution: s[0]=1, s[1]=0
    A[0][0]=3;
    A[0][1]=2;
    A[1][0]=4;
    A[1][1]=-1;
    b[0]=3;
    b[1]=4;
    */

    /*
    //sample problem - is diagonally dominant | solution: s[0]=1, s[1]=1
    A[0][0]=2;
    A[0][1]=1;
    A[1][0]=1;
    A[1][1]=2;
    b[0]=3;
    b[1]=3;
    */

    /*
    //3x3 example from wikipedia "gaussian elimination"| solution: s[0]=2, s[1]=3, s[2]=-1
    A[0][0]=;
    A[0][1]=1;
    A[0][2]=-1;
    b[0]=8;

    A[1][0]=-3;
    A[1][1]=-1;
    A[1][2]=2;
    b[1]=-11;

    A[2][0]=-2;
    A[2][1]=1;
    A[2][2]=2;
    b[2]=-3;
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
    cout<<setw(n*10)<<"A"<<setw(10)<<"s (x)"<<setw(10)<<"b"<<setw(10)<<"e"<<endl;
    for(int i=0; i<n; i++)
    {
        for(int j=0; j<n; j++){cout<<setw(10)<<A[i][j];}
        cout<<setw(10)<<s[i];
        cout<<setw(10)<<b[i];
        cout<<setw(15)<<e[i];
        cout<<endl;
    }
    cout<<endl<<"2-norm: "<<norm<<endl;
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

void matrix::solve()//solve by gaussian elimination
{
    /*
    //not sure why we need to switch the row with the largest entry with the leading row...
    double j=0;
    double largest;
    double b[n];

    for(int i=0; i<n; i++)
    {
        for(int j=0; j<n; j++)
        {
            //scan
            if(A[i][j]>largest)largest=A[i][j];

            //switch
            for(int i=0; i<n; i++)
            {
                b[i]=A[1][j];//store row 1 in temporary array
            }

            for(int i=0; i<n; i++)
            {
                b[i]=A[i][1];//replace the first row with the largest row
            }

            for(int i=0; i<n; i++)
            {
                //b[i]=A[i][1];//replace the largest row with the stored first row
            }

        }
        //cout<<largest<<endl;
        //largest=0;
    }
    */

    //int r=0;//row
    //int c=0;//column
    int z=1;//counter
    double leading_entry=0;//leading entry in that row/column
    double leading_row_entry=0;//entry in the leading row
    double current_row_entry=0;//entry in the row that is currently being normalized
    double temp;//variable that is multiplied/divided to the leading_row_entry

    for(int i=0; i<n; i++)//the big loop---will be used to denote the leading row/column, nOT A[i][j]
    {
        //normalize row by dividing the leading row by the leading entry
        leading_entry = A[i][i];
        for(int c=0; c<n; c++)
        {
            A[i][c]=A[i][c]/leading_entry;
        }
        b[i]=b[i]/leading_entry;
        //leading_entry=A[i][i];//should be 1

        //normalize column by adding a multiple of the leading row
        for(int r=z; r<n; r++)
        {
            temp=A[r][i];//number you divide or multiply by
            for(int c=0; c<n; c++)
            {
                leading_row_entry=A[i][c];
                current_row_entry=A[r][c];
                current_row_entry=current_row_entry+((-temp)*leading_row_entry);
                A[r][c]=current_row_entry;//do it
            }
            b[r]=b[r]+((-temp)*b[i]);
        }
        z++;//to make sure it normalized below zero
    }

    //backsubstitution
/*
    //finding the pattern
    s[n-1]=b[n-1];//guaranteed
    s[n-2]=b[n-2]-A[n-2][n-1]*s[n-1];
    s[n-3]=b[n-3]-A[n-3][n-1]*s[n-1]-A[n-3][n-2]*s[n-2];

*/
   s[n-1]=b[n-1]; /*/A[n-1][n-1];*/
   for(int i=(n-2); i>=0; i--)
   {
       temp = b[i];
       for(int j=(i+1);j<n;j++)
       {
           temp -= (A[i][j] * s[j]);//b-A*s for each variable
       }
       s[i] = temp; /*/ A[i][i];*/
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
    //catch division by 0
    if(snorm==0){norm=0;}
    else{norm=enorm/snorm;}
}
