#include<iostream>
#include<ctime>
#include<cmath>
#include<iomanip>
#include<fstream>
#include"algorithm.h"
using namespace std;

void veiw_display1(double A[3][4])
{
     
     for (int i=0; i<3; i++)
     {
          for (int j=0; j<4; j++)
          {
              cout<<setprecision(2)<<A[i][j]<<"   ";
          }
                   
          cout<<endl;
     }
}
void veiw_X_Y(double X[2][100])
{
     
     for (int i=0; i<100; i++)
     {
          for (int j=0; j<2; j++)
          {
              cout<<setprecision(2)<<X[j][i]<<"     ";
          }
                   
          cout<<endl;
     }
}
void veiw_displayVect(double b[100])
{
     
    for (int i=0; i<100; i++)
     {
          //for (int j=0; j<1; j++)
            cout<<b[i]<<"     ";
                   
          cout<<endl;
     }
}
double poly_orth(float x, double A[3][4], int k)
{
   double q;
   double p[k+1];
   switch(k)
   {
      case 0:
         return 1;
      case 1:
         return (x-A[0][1]);
      default:
      {
         p[1]=1;
         p[2]=x-A[2][1];
         for(int j=2; j<=k; j++)
         {
              p[j+1] = (x - A[2][j])*p[j] - A[3][j]*p[j-1];       
         }
         q=p[k+1];
      }         
   }        
}
double least_square(double a, double x[100], double y[100], double w[100], double A[3][4])
{
    double f=0.0;
    double s=1.0;
    
    for(int k=1; k<=4; k++)
    {
       A[3][k]=0;
       if(k>1)
       {
          for(int i=1; i<=4; i++)
          {
             A[3][k]+=w[i]*x[i]*poly_orth(x[i], A, k-1)*poly_orth(x[i], A, k-2);        
          }
          A[3][k]/=s;       
       }
       A[2][k]=0;
       s=0;
       for(int i=1; i<=100; i++)
       {
          A[2][k]+=w[i]*x[i]*poly_orth(x[i], A, k-1)*poly_orth(x[i], A, k-1);
          s+=w[k]*poly_orth(x[k], A, i-1)*poly_orth(x[k], A, k-1);
                  
       }
       A[2][k] /=s;     
    }
    for (int i = 1; i <= 4; i++)
    {
         s = 0; 	
         A[1][i] = 0;
         for (int k = 1; k <= 100; k++)
         {
            A[1][i] += y[k]*poly_orth(x[k],A, i-1);
            s += w[k]*poly_orth(x[k],A,i-1)*poly_orth(x[k],A,i-1);
         }
         A[1][i] /= s;
    }
    for(int i=1; i<4; i++)
    {
       f += A[1][i]*poly_orth(a,A,i-1);
    }
    return f;       
       
}
int main()
{
    //the number of rows and columns in the matrix
    int k;
    double sum=0.0;
    double sum_b=1.0;
    double random=0.0;
    double matrix[3][4];//the matrix A
    double X_matrix[2][100];
    double Y_matrix[2][100];
    double x[100];
    double w[100];
    double b[100];
    double a=0.0;
    
    cout<<"****************Least Common Squares Method*******************"<<endl;
    cout<<endl;
    srand(time(NULL)); 
    for (int i=0; i<3; i++)//generates the matrix A...rows
    {
        for(int j=0; j<4; j++)//columns
        {
          matrix[i][j]=((rand()%10)/2.0)+1.0;
          random=rand()%2;
          if(random==1)
          {
             matrix[i][j]=matrix[i][j]*-1;          
          }
          if(i!=j)
          {
             sum=sum+abs(matrix[i][j]);     
          }
        }
        matrix[i][i]=sum;
        sum=0;
        //cout<<endl;
        
    }
    cout<<"          Coefficients           "<<endl;
    //veiw_display1(matrix);
    cout<<endl;
    
      
      for(int i=0; i<100; i++)//rows
      {
          a=3.2*(i-1)/99;
          w[i]=1.0;
          x[i]=a;
          b[i]+=a*(a-1)*(a-3);
          //for(int j=0; j<2; j++)//columns
          //{
             X_matrix[1][i]=a;
             Y_matrix[1][i]=b[i];
          //}    
      }
      least_square(0, x, b, w, matrix);
      veiw_display1(matrix);
      for(int i=0; i<100; i++)
      {
          X_matrix[0][i]=x[i];
          Y_matrix[0][i]=least_square(X_matrix[1][i], x, b, w, matrix);         
      }
      
      cout<<endl;
      cout<<"***************************************************************"<<endl;
      cout<<"                        Least Common Squares                   "<<endl;
      cout<<"***************************************************************"<<endl;
      cout<<endl;
      cout<<"      Matrix X      "<<endl;
      cout<<endl;
      veiw_X_Y(X_matrix);
      cout<<endl;
      cout<<"--------------------"<<endl;
      cout<<"      Matrix Y      "<<endl;
      cout<<endl;
      veiw_X_Y(Y_matrix);
      //cout<<endl;        
      //veiw_displayVect(c);
    
   system("pause");
   return 0; 
}
