#include <iostream>
#include <cmath>
#include <ctime>
#include <list>
#include <fstream>
using namespace std;

#include "normal.h"
#include "poly.h"
double eval(double x, double c[], double e[]);

int main()
{
    srand(time(NULL));
    poly F;
    F.add(2,2);
    F.add(-3,1);
    F.add(5,0);
    F.showp();

    double c[3];
    double e[3];
    F.getvalues(c,e);
    for(int i=0; i<3; i++)
    {
       cout<<c[i]<<" "<<e[i]<<endl;
    }

    double v, x, xleft, xright;
    xleft = -2;//this is where it starts to put in x
    xright = 4;//it goes up to this limit for x
    cout<<xleft<<", "<<xright<<endl;
    int step = 10;//number of segments
    double dx = ((xright-xleft)/step);//breaks down the limits into 10 steps
    
    x=xleft;
    
    v=eval(x,c,e);
    cout<<v<<endl;
    fstream fout;
    fout.open("data.txt",ios::out);

    cout<<"x= "<<x<<" f(x)= "<<v<<"  ";
    v=normal(v,0.2);
    cout<<"corrupted ~f(x)= "<<v<<endl;
    fout<<x<<" "<<v<<endl;
    //dx=(dx/3);
    for(int i=0; i<step; i++)
    {

       
       x=(x+dx);//this is the count
       v=eval(x,c,e);
       cout<<"x= "<<x<<" f(x)= "<<v<<"  ";
       v=normal(v,0.2);
       cout<<"corrupted ~f(x)= "<<v<<endl;
       fout<<x<<" "<<v<<endl;
    }

    fout.close();
    system("pause");
    return 0;
}

double eval(double x, double c[],double  e[])//this evaluates the function
{
    
    double v=0;
    for(int i=0; i<3; i++)
    {
        v = v+c[i]*pow(x,e[i]);
    }
    return v;
}

