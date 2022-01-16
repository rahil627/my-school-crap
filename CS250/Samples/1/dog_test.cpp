#include <string>
#include <iostream>
using namespace std;

#include "dog.h"

int main()
{
doghouse D1;
dog * woof;
woof = new dog("tosca");
cout<<"testing the dog"<<endl;
woof->show();
cout<<endl<<endl<<"testing the doghouse"<<endl;
D1.show();
cout<<"putting dog in the house"<<endl;
D1.adddog(woof);
D1.show();

system("pause");
return 0;
}
