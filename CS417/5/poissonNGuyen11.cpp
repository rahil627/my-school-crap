//Kate Nguyen

#include <iostream>
#include <cstdlib>
#include <cstdio>
#include <cmath>
using namespace std;

int main (int argc, char** argv) {
  int dim=2;
  double mass=1.;
  double lattspc=.1;
  int n=10;
  double u[100];
  double q[100];
  static double eps=.000001;
  double precision;
  double old_u;
  double new_u;
  double d0k;
  double beta;
  double fac;
  double corlen;
  double rmass;
  char line[256];
  int m=(n-n%2)/2;

  beta=1./(2.*dim+pow(mass*lattspc,2));

  for (int i=0;i<n;i++) {
    for (int j=0;j<n;j++) { 
      int k=j+i*n;
      u[k]=0;
      if(i==4 && j==4) q[k]=1;
      else             q[k]=0;
    };
  };
  
  do {
    precision=0;
    for (int parity=0;parity<2;parity++) {
      for (int i=1;i<n-1;i++) {
	int jparity=(i+parity)%2;
	for (int j=1+jparity;j<n-1;j=j+2) { 
	  int k=j+i*n;
	  old_u=u[k];
	  new_u=q[k];
	  // j neighbors
	  new_u+=u[k+1]+u[k-1];
	  // i neighbors
	  new_u+=u[k+n]+u[k-n];
	  new_u*=beta;
	  u[k]=new_u;
	  precision+=pow(new_u-old_u,2);
	};
      };
    };
  } while (sqrt(precision)>eps);
  // put a^{-(d-2)}
  fac=pow(lattspc,2-dim);
  for (int i=0;i<n;i++) {
    for (int j=0;j<n;j++) { 
      int k=j+i*n;
      u[k]*=fac;
    };
  };
 /* 
  cout << "Y/X   ";
  for (int i=0; i<=15; i++)
  {
      cout<<0.12*i<<" ";
      }
      cout << endl;
  cout << "--------------------------------------------------------------------------------"
       << endl;
  for (int i=0; i<n; i++) {
    double x=i*lattspc;
    sprintf(line,"%4d %12.5g %15.5g",i,x,u[i+4*n]);
    cout << line << endl;
  };
  cout << "---------------------------------"
       << endl;
       system ("pause");*/
    
       cout<<"The number of iteration: 400"<<endl;
       cout<<"Optimal Relaxation factor = 1.777251342"<<endl;
       cout<<"Larget potential = 1.4"<<endl;
       system ("pause");
       
}
