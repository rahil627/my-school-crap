//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575
//compiled with GNU GCC

//pre-processors----------------------------------------------------------------------------------
#include<iostream>
#include<fstream>
#include<iomanip>
#include<cmath>
#include<ctime>
using namespace std;

//functions---------------------------------------------------------------------------------------
void veiw_display(double **a, int c, double *b)
{
     for(int i=0; i<c; i++)
     {
           for (int j=0; j<c; j++)
           {
               cout<<setw(10)<<a[i][j];
           }
           cout<<setw(10)<<"x: "<<setprecision(3)<<b[i];
           cout<<endl;
      }
}
void veiw_display1(double **a, int c)
{
     for(int i=0; i<c; i++)
     {
           for (int j=0; j<c; j++)
           {
               cout<<setw(10)<<setprecision(3)<<a[i][j];
           }
           cout<<endl;
      }
}
void veiw_displayVect(double *y, int c)
{
     for(int i=0; i<c; i++)
     {
           cout<<"y: "<<setprecision(3)<<y[i];
           cout<<endl;
      }
}

void veiw_display2(double **a, int c, double * y, double *b)
{

     for(int i=0; i<c; i++)
     {
           for (int j=0; j<c; j++)
           {
               cout<<setw(10)<<setprecision(3)<<a[i][j]<<setw(10);
           }
            cout<<"y: "<<setprecision(3)<<y[i]<<setw(10);
            cout<<"b: "<<setprecision(3)<<b[i];

           cout<<endl;
      }
}

void back_substitute(int n, double **a, double *x, double * b)//Ux=y
{
    for (int i=n-1;i>=0;i--)
    {
		x[i]=b[i];
		for (int j=n-1;j>i;j--)
        {
			x[i] -= a[i][j]*x[j];
		}
		x[i] /= a[i][i];
	}
     return;
}
double vector_norm(double * x, int n)
{
       //the vector norm is ||x||=the sqrt( x[1]^2+x[2]^2....x[n]^2)
       double norm=0.0;
       double product=0.0;

       for (int u=0; u<n; u++)
       {
            product+=pow(x[u], 2);
       }
       norm=abs(sqrt(product));
       return norm;
}
double dotProduct(double * x, double * y, int n)
{
       double dot_product=0.0;

       for (int t=0; t<n; t++)//here is the dot product
       {
            dot_product+=y[t]*x[t];
       }
       return dot_product;
}

//main--------------------------------------------------------------------------------------------
int main()
{
    double **A;//matrix A
    double * x;//solution vector
    double * z;
    double m;
    int k=0;
    double sum=0;
    double random=0.0;
    int N=3;
    A=new double *[N];
    x=new double [N];
    z=new double [N];

    srand(time(NULL));

    for (int i=0; i<N; i++)//generates the matrix A
    {
        A[i]= new double [N];
    }

    A[0][0]=-1;
    A[0][1]=1;
    A[0][2]=0;

    A[1][0]=-1;
    A[1][1]=1;
    A[1][2]=0;

    A[2][0]=0;
    A[2][1]=-1;
    A[2][2]=1;

    for (int y=0; y<N; y++)
    {
      x=new double[N];
    }

    x[0]=711;
    x[0]=1177;
    x[0]=475;

    veiw_display(A, N, x);

    for (int i=0; i<N; i++)
    {
          for (int j=0; j<N; j++)
          {
               A[i][j]=((double) (rand()%10))/2;
               random=rand()%2;
               if (random==1)
               {
                   A[i][j]=A[i][j]*-1;
               }

               if (i!=j)
               {
                   sum=sum+abs( A[i][j]);
               }


           }
           A[i][i]=sum;
           sum=0;

    }


      for (int row=0; row<N; row++)//generates the solution matrix.
      {

         x[row]=( (double) (rand()%4) )/2.0;
         random=rand()%2;
         if(random==1)
         {
             x[row]=x[row]*-1;
         }

      }

      ofstream fout;
      fout.open("Convergence.txt");
      //veiw_display(A, N, x);
      cout<<endl;

      float e=0.00001;
      cout<<"tolerance: "<<e<<endl;
      m=100;
      cout<<"Number of Steps: "<<m<<endl;//number of steps
      cout<<endl;

     double dot_product=0.0;
     double t=0.0;
     double * r;
     r=new double[N];
     cout<<"k"<<setw(30)<<"Dominant Eiganvalue"<<setw(15)<<"Convergence"<<endl;
     fout<<"k"<<setw(30)<<"Dominant Eiganvalue"<<setw(15)<<"Convergence"<<endl;
      do
      {
           double s=vector_norm(x, N);
           for (int i=0; i<N; i++)
           {
               z=new double [N];
           }
           for (int i=0; i<N; i++)
           {
               z[i]=x[i]/s;
           }

           double temp=0.0;//multiplying Az and storing it into x.
           int i=0;
           for (int row=0; row<N; row++)//the row of the x matrix
           {
               for (int col=0; col<N && i<N; col++)
               {
                    temp+=((A[row][col])*z[col]);
                    x[row]=temp;
               }
               temp=0.0;
           }

          double dominant_eiganvalues=dotProduct(x, z, N)/dotProduct(z, z, N);
          for(int j=0; j<N; j++)
          {
               r=new double[N];
          }
          for(int i=0; i<N; i++)
          {
               r[i]=(dominant_eiganvalues*z[i])-x[i];

          }
          t=vector_norm(r, N);

           k=k+1;
           cout<<k<<setw(20)<<dominant_eiganvalues<<setw(20)<<t<<endl;
           fout<<k<<setw(20)<<dominant_eiganvalues<<setw(20)<<t<<endl;
      }
      while((t>e) && (k<m));
      fout.close();

   system("pause");
   return 0;
}
