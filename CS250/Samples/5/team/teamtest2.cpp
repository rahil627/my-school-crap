 #include<iostream>
#include<string>
using namespace std;

#include "team.h"

int main()
{
team *t;
t=new team;
t->setcoach("Holgram");
team*curr=t;
curr->makenew();
curr=curr->getnext();
curr->setcoach("Jackson");
curr->makenew();
curr=curr->getnext();
curr->setcoach("Riley");


curr=t;
while(curr!=NULL)
{
curr->display();
curr=curr->getnext();
}

system("pause");
return 0;}
