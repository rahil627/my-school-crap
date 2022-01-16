#include <iostream>
#include <string>
using namespace std;

#include "goose.h"

int main()
{
 goose Mom;
 goose * gptr=NULL;

 gptr = new goose;


 Mom.display();

 cout<<"_----------------------------------_"<<endl;

 Mom.setwspan(28);
 Mom.setwt(9);
 Mom.setbanded(true);
 Mom.setband("VA123-45KR21");
 Mom.setgender('f');

 Mom.display();

cout<<endl<<endl;
cout<<"----------------testing gptr---"<<endl;
 gptr->display();
 gptr->setwspan(22);
 gptr->setwt(11);
 gptr->setgender('m');
 gptr->display();


system("pause");
return 0;
}
