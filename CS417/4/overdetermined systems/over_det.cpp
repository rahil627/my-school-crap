#include<iostream>
#include<ctime>
#include<cmath>
#include<iomanip>
#include<fstream>
#include"algorithm.h"
using namespace std;

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
int dotProduct(int x[4], int y[4])
{
       int dot_product=0;
       
       for (int t=0; t<4; t++)//here is the dot product
       {
            dot_product+=y[t]*x[t];    
       }
       return dot_product;   
}

int main()
{
    //the number of rows and columns in the matrix
    
    
    double squared;
    double squared_i;
    double cos;
    double cos_i;
    double sin_i;
    double sin;
    double r;
    double r_i;
    double delta;
    double beta;
    
    double **G;
    double **A;//the matrix A
    double * x;//solution matrix
    //double * B;
    double * b;
   
    
    double m;
    int k=0;
    double sum=0;
    double random=0.0;
    int N=3;
    A= new double *[N];//making a new matrix with nxn rows and columns
    G= new double *[N];
    x= new double [N];
    //B= new double [N];
    b=new double [N];
    
   
    
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
     
      
      
      for (int y=0; y<N; y++)
      {
          b=new double[N];
      }
      for (int row=0; row<N; row++)//generates the solution matrix.
      {
                        
         b[row]=( (double) (rand()%4) )/2.0;
         random=rand()%2;
         if(random==1)
         {
             b[row]=b[row]*-1;
         }
                        
      }           
      cout<<endl;
      
      
      
      for (int y=0; y<N; y++)
      {
          x=new double[N];
      }
      for (int i=0; i<N; i++)//generates the matrix A
      {
        G[i]= new double [N];
      }
      for(int j=0; j<N; j++)
      {
       
          for(int i=0; i<N; i++)
          {
              squared_i=(pow(A[i][j], 2.0)+pow(A[i+1][j], 2.0));
              r_i=sqrt(squared_i);
              cos_i=A[i][i]/r_i;
              sin_i=-A[i][i]/r_i;
              delta=cos*A[i][j];
              beta=sin*A[i][j];
              G[i][j]=delta-beta;
              G[i][j]=delta+beta;
              
              G[j][j]=cos*A[j][j]-sin*A[j][j];
              G[j][j]=0;
              x[j]=b[j];
              //cout<<"there: "<<x[i]<<"  "<<"test: "<<b[i]<<endl;
              b[j]=cos*x[j]-sin*b[j];
              cout<<G[j][i]<<"    ";
              
              //cout<<"here: "<<b[i]<<"            ";
          }
          
          //
       }
            
                   
          
      
      
    
   system("pause");
   return 0; 
}
