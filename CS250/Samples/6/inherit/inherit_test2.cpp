#include <iostream>
using namespace std;

#include "inherit.h"
void showall(brown *h);

int main()
{
    brown * head;
    brown * curr;  //actually moves up and down the list
    red * rcurr;   //used to get to red functions
                   //used to also ADD new red objects
    black * bcurr; //used to get to the black functions
                   //used to also ADD new black objects

   rcurr = new red(1,'A');
   head = rcurr; //THIS IS IMPLIED..

   bcurr = new black(2, 'B');
   head ->setnext(bcurr); 

   curr = head;

   rcurr= new red(3,'C');
   curr=curr->getnext();
   curr->setnext(rcurr);

   bcurr = new black(4,'D');
   curr=curr->getnext();
   curr->setnext(bcurr);

showall(head);
 


system("pause");
return 0;


}

/*
void showall(brown * h)
{
    while(h!=NULL)
    {             
    h->display();
    h=h->getnext();

    }
}
*/
void showall(brown * h)
{
int t;
red * rtemp;
black * btemp;

    while(h!=NULL)
    {             
    t=h->gettype();
    switch(t)
             {
             case 1:{rtemp = (red*)h;
                     rtemp->display();}break;
             case 2:{btemp = (black*)h;
                     btemp->display();}break;
             }
    h=h->getnext();
cout<<"-----------------------------------"<<endl;

    }
}

