#include <iostream>
using namespace std;

#include "mixed_list.h"

int main()
{
     beta * Bptr;
     Bptr = new beta;
     Bptr ->display();

     D * Dptr;
     Dptr = new D(7);
     Dptr->display();

     E* Eptr;
     Eptr = new E(88);
     Eptr->display();
 

     delete Bptr;
     Bptr = Dptr; 
     Bptr ->display();

     delete Bptr;
     delete Dptr;
     delete Eptr;
system("pause");
return 0;
}
