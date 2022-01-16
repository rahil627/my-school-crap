#include <iostream>
#include <string>
using namespace std;
#include "polygon.h"

int main()
{
 polygon P1; 
 P1.sets(5);
 P1.display();
 cout<<endl<<endl<<"---------------"<<endl;
 
 triangle T1("scalene");
 T1.display();

 cout<<"just to make sure"<<endl;
 P1.display();

system("pause");
return 0;
}
