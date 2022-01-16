//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575
//compiled with GNU GCC

//pre-processors----------------------------------------------------------------------------------
#include<iostream>
#include<fstream>
#include<iomanip>
#include<cmath>
#include<list>
using namespace std;
#include"5.h"

//main--------------------------------------------------------------------------------------------
int main()
{
    poly F;
    float e, y;
    int m=0;
    float x_1, x_0, x_2, f0, f1, f2, estimated_root;
    //estimated_root=0.0;
    int k=0.0;
    x_0=-2.0;//lower boundary
    x_1=0.0;//upper boundary
    x_2=0.0;
    e=0.00001;
    //m=2.0;

    F.add(2, 2);
    F.add(4, 1);
    F.add(2, 0);
    //F.showp();


    //cout<<"Number of iterations?"<<endl;
    //cin>>m;
    display();
    //cout<<endl;
    //cout<<"Iterations"<<setw(20)<<"midpoint"<<setw(20)<<"interval length"<<setw(25)<<"magnitude of f(x_2)"<<endl;
    f0=F.eval(x_1);

    f2=F.eval(x_2);
    do
    {

         x_2=(x_0+x_1)/2;
         f2=F.eval(x_2);
         if((f0*f1)<0 )
         {
              x_1=x_2;
         }
         else
         {
             x_0=x_2;
             f0=f2;

         }
         y=(float) fabs(f2);

         //cout<<setw(5)<<k<<setw(23)<<x_2<<setw(15)<<setprecision(3)<<x_1-x_0<<setw(22)<<setprecision(5)<<y<<endl;

         k++;

    }

   while((y > e) && (k < m));
   estimated_root=x_2;
   //cout<<"root: "<<estimated_root<<endl;

    system("pause");
    return 0;
}
