#include <iostream>
using namespace std;

#include "inherit.h"
void showall(brown *h);
int menu();

int main()
{
    brown * head=NULL;
    brown * curr;  //actually moves up and down the list
    red * rcurr;   //used to get to red functions
                   //used to also ADD new red objects
    black * bcurr; //used to get to the black functions
                   //used to also ADD new black objects
    green * gcurr;
int val = 1;
char let = 'A';

int option = 9;
while(option!=0)
                {
                option = menu();
                switch(option)
                              {
                              case 1:
                                  {rcurr = new red(val,let);
                                   if(head==NULL){head = rcurr; curr=head;}
                                   else{curr->setnext(rcurr);
                                        curr=curr->getnext();
                                       }
                                    val++;
                                    let++;
                                   }break;
                             case 2:
                                  {bcurr = new black(val,let);
                                   if(head==NULL){head = bcurr; curr=head;}
                                   else{curr->setnext(bcurr);
                                        curr=curr->getnext();
                                       }
                                   val++;
                                   let++;  
                                   }break;
                              case 3:
                                   {
                                   gcurr = new green(val,let);
                                   if(head==NULL){head = gcurr; curr = head;}
                                   else{curr->setnext(gcurr);
                                        curr=curr->getnext();
                                        }
                                   val++;
                                   let++;
                                   }break;
                              }

                }

showall(head);
 


system("pause");
return 0;


}

void showall(brown * h)
{
int t;
red * rtemp;
black * btemp;
green * gtemp;

    while(h!=NULL)
    {             
    t=h->gettype();
    switch(t)
             {
             case 1:{rtemp = (red*)h;
                     rtemp->display();}break;
             case 2:{btemp = (black*)h;
                     btemp->display();}break;
             case 3:{gtemp = (green*)h;
                     gtemp->display();}break;
             }
    h=h->getnext();
cout<<"-----------------------------------"<<endl;

    }
}




int menu()
{
int option;
 cout<<"1. add new red"<<endl;
 cout<<"2. add new black"<<endl;
 cout<<"3. add new green"<<endl;
 cout<<"0. quit"<<endl;
cin>>option;
return option;
}
