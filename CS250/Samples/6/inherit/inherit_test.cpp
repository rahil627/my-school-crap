#include <iostream>
using namespace std;

#include "inherit.h"

int main()
{
 red R1(1,'a');
 R1.display();

 cout<<endl<<"restricting to the base class"<<endl;
 brown * bptr;
//bptr looks at the BROWN portion of the red object
 bptr = &R1;
 bptr->display();

 red * rptr;
//we have to tell rptr that bptr is ACTUALLY looking at a red object
//this is an EXPLICIT cast
 rptr = (red*)bptr;
 cout<<endl<<"downcasting to the more complex red object"<<endl;
 rptr->display();

system("pause");
return 0;


}
