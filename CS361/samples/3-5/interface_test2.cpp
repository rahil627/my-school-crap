#include <iostream>
#include <cmath>
#include <ctime>
#include <list>
using namespace std;
#include "normal.h"
#include "sensor2.h"
#include "flechette.h"

int main()
{
    flechette F1(100,50,50);
    
    sensor * sptr;
    

//creating a list of sensors for a flechette
    for(int i=0; i<25; i++)
        {
        sptr = new sensor(i,'V');
        if(i%2 == 0){
        F1.addsensor(sptr);}
        else{cout<<"sensor #"<<i<<" not added"<<endl;}
        }
  

//asking a flechette for a time step report.
  for(int t=0; t<5; t++)
    {
    cout<<"report for time: "<<t<<endl;
    F1.report();
    system("pause");
    }
    
system("pause");
return 0;
}