#include <stdio.h>
#include <stdlib.h>
#include <string>
using namespace std;


// Floyd's All pairs shortest path algorithm (O (n^3) )
// input is adjacency matrix output is matrix of shortest paths
// C is the adjacency matrix
// n is the order of the square matrix
// A is the all pairs shortest paths matrix
// we assume that A is allocated by the caller
int ComputeFloydAPSP(int *C, int n, int *A, string* s)
{

  int i,j,k;

  // set all connected positions to 1
  // and all unconnected positions to infinity
  for (i=0; i<n; i++)
  {
    for (j=0; j<n; j++)
    {
      if ( *(C+i*n+j) == 0)
      {
        *(A+i*n+j) = 999999999;    // Does this look like infinity?
      }
      else
      {
        *(A+i*n+j) = 1;
        char sz[3]="";
        sprintf(sz,"%d",j+1);
        s[i*n+j]=sz;
      }
    }
  }

  // set the diagonals to zero
  for (i=0; i<n; i++)
  {
    *(A+i*n+i) = 0;
  }

  // for each route via k from i to j pick any better routes and
  // replace A[i][j] path with sum of paths i-k and j-k
  for (k=0; k<n; k++)
  {
    for (i=0; i<n; i++)
    {
      for (j=0; j<n; j++)
      {
        if ( *(A+i*n+k) + *(A+k*n+j) < *(A+i*n+j) )
        {
          // A[i][j] = A[i][k] + A[k][j];
          *(A+i*n+j) = *(A+i*n+k)+ *(A+k*n+j);
          //s[i*n+j]=s[i*n+j]+s[i*n+k]+s[k*n+j];
          s[i*n+j]=s[i*n+k]+","+s[k*n+j];
        }
      }
    }
  }
  //return 0;
}    // Floyd's algorithm


// this is for testing Floyd's algorithm
// demonstrates the allocation and
// deallocation of memory for the matrices
void FloydTest()
{

  // allocate the entire matrix in one linear array
  // trying to allocate it as an array of pointers to arrays
  // didn't quite work, possibly because the [] and * notation
  // aren't quite replaceable
  int n=10;
  int *C=new int[n*n];

  C[0 ]=0;C[1 ]=1;C[2 ]=0;C[3 ]=0;C[4 ]=0;C[5 ]=0;C[6 ]=0;C[7 ]=0;
          C[8 ]=0;C[9 ]=0;
  C[10]=1;C[11]=0;C[12]=1;C[13]=0;C[14]=0;C[15]=0;C[16]=1;C[17]=0;
          C[18]=0;C[19]=0;
  C[20]=0;C[21]=1;C[22]=0;C[23]=1;C[24]=0;C[25]=0;C[26]=0;C[27]=0;
          C[28]=0;C[29]=1;
  C[30]=0;C[31]=0;C[32]=1;C[33]=0;C[34]=1;C[35]=0;C[36]=0;C[37]=0;
          C[38]=0;C[39]=0;
  C[40]=0;C[41]=0;C[42]=0;C[43]=1;C[44]=0;C[45]=1;C[46]=0;C[47]=0;
          C[48]=0;C[49]=0;
  C[50]=0;C[51]=0;C[52]=0;C[53]=0;C[54]=1;C[55]=0;C[56]=1;C[57]=0;
          C[58]=0;C[59]=0;
  C[60]=0;C[61]=1;C[62]=0;C[63]=0;C[64]=0;C[65]=1;C[66]=0;C[67]=1;
          C[68]=0;C[69]=0;
  C[70]=0;C[71]=0;C[72]=0;C[73]=0;C[74]=0;C[75]=0;C[76]=1;C[77]=0;
          C[78]=1;C[79]=1;
  C[80]=0;C[81]=0;C[82]=0;C[83]=0;C[84]=0;C[85]=0;C[86]=0;C[87]=1;
          C[88]=0;C[89]=0;
  C[90]=0;C[91]=0;C[92]=1;C[93]=0;C[94]=0;C[95]=0;C[96]=0;C[97]=1;
          C[98]=0;C[99]=0;


  int* A = new int[n*n];
  string* s = new string[n*n];
  printf("Initial matrix\n");
  int i;
  for(i=0;i<n;i++)
  {
    for(int j=0;j<n;j++)
    {
      printf("%d ",*(C+i*n+j));
    }
    printf("\n");
  }


  ComputeFloydAPSP (C,n,A,s);

  printf("Final shortest distances\n");
  for(i=0;i<n;i++)
  {
    for(int j=0;j<n;j++)
    {
      printf("%d ",*(A+i*n+j));
    }
    printf("\n");
  }
  printf("End of All pairs Shortest paths\n");
  for(i=0;i<n;i++)
  {
    for(int j=i+1;j<n;j++)
    {
      printf("path from %d to %d is %s\n",i+1,j+1,s[i*n+j].c_str());
    }
    printf("\n");
  }

    delete [] A;
    delete [] C;
    delete [] s;

}    // end of FloydTest

int main()
{
  FloydTest();
  char c;
  scanf("%c",&c);
  system("pause");
  return 0;
}
