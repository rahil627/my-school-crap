#include <iostream>
#include <cmath>
#include <math.h>
#include <ctime>
#include <list>
using namespace std;
#include "normal.h"
#include "sensor2.h"
#include "flechette.h"
#include "coordinate.h"

int main()
{
  /*  flechette F1(100,2,10);
    flechette F2(200,4,10);
    flechette F3(300,6,10);
    sensor * sptr;
    srand((unsigned)time(0));

//creating a list of sensors for a flechette
    for(int i=0; i<25; i++)
        {
        sptr = new sensor(i,'V');
        //if(i%2 == 0){}
        F1.addsensor(sptr);
        F2.addsensor(sptr);
        F3.addsensor(sptr);
        //else{cout<<"sensor #"<<i<<" not added"<<endl;}
        }
 */ 
double originY = 100;
double originX = 100;

cout<< "Give the coordinates for you tank, and its base signal strength...."  << endl;
cout<< "x: ";
cin >> originX;
cout<< "y: ";
cin>> originY;
cout<< endl;

coordinate * cptr;
double angle = 0;

int x, y;
int gate = 0;
double temp;
list <coordinate> clist;
list <coordinate>::iterator citr;
 for (int time = 1; time <= 10; time++)
{
 for (int index = 0; index < 90; index++) 
            {
             
               angle = index * 4;
               angle = (angle * 180)/3.14; 
               
               temp =    sin(angle);
               if (temp >= .9)
               temp = 1;
               if (temp <= -.9)
               temp = -1;
               y = (int)(originY + (int)(temp * time));
               temp =    cos(angle);
               if (temp >= .9)
               temp = 1;
               if (temp <= -.9)
               temp = -1;          
               x = (int)(originX + (int)(temp * time));
              // cout << "Xcoor " << x << " , Ycoor " << y << " strength of " << 11-time << endl;
    citr=clist.begin(); 
    while (citr!=clist.end() && gate == 0 )
        { 
          
          if (x == citr->getx())
             {
              if (y == citr->gety())
              { 
                gate = 1; 
              }
             }              
        citr++;
        } 
     
     if (gate == 0)
     {
     
     cptr = new coordinate(x, y, time); 
     clist.push_back(*cptr);          
     }
     gate = 0;
             }
citr=clist.begin();  
while (citr!=clist.end())
        { 
        citr->display();                     
        citr++;
        } 
system("pause");
cout << "BREAK" << endl;
}



/*
//asking a flechette for a time step report.
  for(int t=0; t<5; t++)
    {
    cout<<"report for time: "<<t<<endl;
    F1.report();
    F2.report();
    F3.report();
    system("pause");
    }
  */  
system("pause");
return 0;
}
