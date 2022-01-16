#include<iostream>
#include<ctime>
#include<cmath>
#include<iomanip>
#include<fstream>
using namespace std;
void veiw_display(double **a, int c, double *b)
{
     
     for(int i=0; i<c; i++)
     {
           for (int j=0; j<c; j++)
           {
               cout<<setw(10)<<setprecision(3)<<a[i][j]<<setw(10);
           }
           cout<<"x: "<<setprecision(3)<<b[i];
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
           //cout<<"b: "<<setprecision(3)<<b[i];
           cout<<endl;
      }
}
void veiw_displayVect(double *y, int c)
{
     
     for(int i=0; i<c; i++)
     {
           
           
           //cout<<setw(10)<<setprecision(3)<<a[i][j];
           
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
void LU(int n, double **A_new, double *b, double **Replacement)
{
    
    double **D;//the matrix A
    //int m=n;
    //double * B;
    //int N=3;
    
    D=new double*[n];
    
    for(int i=0; i<n; i++)
    {
        for(int j=0; j<n+1; j++)
        {    
            D[i][j]=new double[n];
        }   
    }
    for (int i = 0; i < n; i++) 
    {
      for (int j = 0; j < n; j++) 
      {
          D[i][j] = A_new[i][j];
      }
      //D[i][n+1] = (float) i; 
    }
   
     
}//DO NOT DELETE



int main()
{
    //the number of rows and columns in the matrix
    double **A;//the matrix A
    double **Replacement;
    double * x;//solution matrix
    //double * B;
    double * z;
   
    
    double m;
    int k=0;
    double sum=0;
    double random=0.0;
    int N=3;
    A= new double *[N];//making a new matrix with nxn rows and columns
    Replacement=new double * [N];
    x= new double [N];
    z=new double [N];
    
   
    
    srand(time(NULL)); 
    for (int i=0; i<N; i++)//generates the matrix A
    {
        A[i]= new double [N];
    }
              
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
    
    for (int i=0; i<N; i++)
    {
        Replacement[i]=new double [N];    
    }
    for (int i=0; i<N; i++)
    {
       for(int k=0; k<N; k++)
       {
           Replacement[i][k]=0;        
       } 
           
    } 
      
      
      for (int y=0; y<N; y++)
      {
          x=new double[N];
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
      
      for (int y=0; y<N; y++)
      {
          x=new double[N];
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
                
      LU(N, A, x, Replacement);
    
   system("pause");
   return 0; 
}
