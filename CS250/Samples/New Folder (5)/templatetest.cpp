#include <iostream>
#include <string>
using namespace std;

#include "swap.h"

int main()
{
      int A, B;
      A = 99;
      B = 77;

      char c1 = 'A';
      char c2 = 'Z';

      string fname = "Ima";
      string lname = "Jerk";

      cout<<"first = "<<A<<" second = "<<B<<endl;
      Swap(A,B);
      cout<<"first = "<<A<<" second = "<<B<<endl;

      cout<<"first = "<<c1<<" second = "<<c2<<endl;
      Swap(c1,c2);
      cout<<"first = "<<c1<<" second = "<<c2<<endl;

      cout<<"first = "<<fname<<" second = "<<lname<<endl;
      Swap(fname,lname);
      cout<<"first = "<<fname<<" second = "<<lname<<endl;


system("pause");
return 0;
}
