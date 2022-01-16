#include<iostream>
#include<ctime>
#include<cmath>
#include<iomanip>
#include<fstream>
#include"algorithm.h"
using namespace std;

void veiw_display1(int A[4][3])
{
     
     for (int i=0; i<3; i++)
     {
          for (int j=0; j<4; j++)
            cout<<A[i][j]<<"     ";
                   
          cout<<endl;
     }
}
void veiw_displayVect(int b[3])
{
     
    for (int i=0; i<4; i++)
     {
          //for (int j=0; j<1; j++)
            cout<<b[i]<<"     ";
                   
          cout<<endl;
     }
}
int scale(int matrix[3][4])
{
      int a;
      double eps=0.0001;
      
      for(int i=0; i<3; i++)
      {
              a=0;
              for(int j=0; j<3; j++)
              {
                  a=max(a, abs(matrix[i][j]));        
              }
       if(a<eps)
           return 0;
       else
       {
           for(int j=0; j<4; j++)
           {
               matrix[i][j]/=a;
           }    
       }
        return 1;     
      } 
}
void col(int x[4], int matrix[3][4])
{
    for(int k=0; k<3; k++)
    {
        x[k]=matrix[k][4];        
    }    
}
void put_col(int B[3][4], int x[4])
{
     
     for(int i=0; i<3; i++)
     {
         B[i][4]=x[i];
     }    
     
}
int LU_Factor(int matrix[3][4], int Q[3][3], int x[3])
{
    int D[3][4];
    double eps=0.001;
    int d, q;
    int delta=1;
    
    for(int i=1; i<=3; i++)
    {
        for(int j=1; j<=3; j++)
        {
            D[i][j]=matrix[i][j];        
        }
        D[i][4]=i;
    }
    
    for(int i=1; i<=3; i++)
    {
        for(int k=i; k<=3; k++)
        {
            for(int l=1; l<=i-1; l++)
            {
               D[k][i]-=D[k][l]*D[l][i];
            }    
        }    
        q=i;
        for(int l=i+1; l<=3; l++)
        {
            if(abs(D[l][i]) > abs(D[q][i]))
               q=l;        
        }
        if(fabs(D[q][i])<eps)
           return 0;
           
        if(q>i)
        {
           for(int l=1; l<=4; l++)
           {
              d=D[q][l];
              D[q][l]=D[i][l];
              D[i][l]=d;
           }
           delta=-delta;        
        }
        for(int k=i+1; k<=3; k++)
        {
            for(int l=1; l<=i-1; l++)
            {
                D[i][k]-=D[i][l]*D[l][k];        
            }
            D[i][k] /=D[i][i];        
        }
        delta *= D[i][i];    
    }
    for(int k=1; k<=3; k++)
    {
        for(int j=1; j<=3; j++)
        {
            x[k]=D[k][4];        
        }      
    }
    return delta;
}
int LU_substitution(int Q[3][3], int p[3], int b[4], int x[3])
{
    int y[3];
    int d[3];
    int k;
    int delta;
    
    delta= Q[1][1];
    for(int i=2; i<=3; i++)
    {
       delta *=Q[i][i];     
    }
    if(delta==0)
    {
       return 0;            
    }
    for(int i=1; i<=3; i++)
    {
       k= p[i];
       d[i]=b[k];       
    }
    for(int m=1; m<=3; m++)
    {
       y[m]=d[m];
       for(int j=1; j<=m-1; j++)
       {
          y[m]-=Q[m][j]*y[j];        
       }
       y[m] /=Q[m][m];
    }
    
    for(int i=3; i>=1; i--)
    {
       x[i]=y[i];
       for(int k=i+1; k<=3; k++)
       {
          x[i]-=Q[i][k]*x[k];
       }        
    }
    
    return 1;
    
}
int LU_Decomposition(int A[3][3], int b[3], int x[4])//takes in n
{
    int Q[3][3];
    //int B[3][3];
    int p[3];
    int c[3];
    int a;
    int r;
    
    int B[3][4];
    
    for(int i=0; i<3; i++)
    {
       for(int j=0; j<4; j++)
       {
           B[i][j]=A[i][j];        
       }        
    }
    put_col(B, b);
    scale(B);
    col(c, B);
    
    if(LU_Factor(B, Q, p))
    {
       LU_substitution(Q, p, c, x);
    }
    else
    {
       r=0;    
    }
    
   return r; 
    
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
    int k;
    int sum=0;
    int sum_b=1;
    int random=0;
    int matrix[4][3];//the matrix A
    int b[4];
    int Aj[4];
    int Ai[4];
    int c[3];
    int D[3][3];
    int x[3];
    
    
    srand(time(NULL)); 
    for (int i=0; i<4; i++)//generates the matrix A
    {
        for(int j=0; j<3; j++)
        {
          matrix[i][j]=((rand()%10)/2);
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
    veiw_display1(matrix);
    cout<<endl;
    
   
      
      for (int row=0; row<4; row++)//generates the solution matrix.
      {
         //for(int col=0; col<1; col++)               
         //{
            b[row]=((rand()%4)/2)+1;
            random=rand()%2;
            if(random==1)
            {
              sum_b=b[row]*-1;
            }
         //}
         b[row]=sum_b;
         sum_b=1;
                        
      }
      veiw_displayVect(b);
      
      for(int i=0; i<3; i++)
      {
          for(int j=0; j<4; j++)
          {
              Ai[j]=matrix[j][i];    
          }
          for(int k=0; k<3; k++)
          {
              for(int l=0; l<4; l++)
              {
                  Aj[l]=matrix[l][k];        
              }
              D[i][k]=dotProduct(Ai, Aj);
          }
          c[i]= dotProduct(Ai, b);            
      }
      cout<<"here"<<endl;
      LU_Decomposition(D, c, x);
      cout<<endl;
       for (int i=0; i<3; i++)
     {
          for (int j=0; j<3; j++)
            cout<<D[i][j]<<"     ";
                   
          cout<<endl;
     }
      //cout<<endl;        
      //veiw_displayVect(c);
    
   system("pause");
   return 0; 
}
