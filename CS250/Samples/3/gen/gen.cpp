#include <iostream>
#include <ctime> //use with random number generators
#include <iomanip>

using namespace std;

#include "gen.h"
gen * makelist(gen * h);
void showlist(gen * h);
gen *bubble(gen * h);

int main()
{
 srand(time(NULL));//set seed for random numbers
 
// int value;
 gen * head = NULL;
head = makelist(head);
showlist(head);
cout<<endl<<endl<<"sorting list"<<endl<<endl;
head=bubble(head);
showlist(head);

system("pause");
return 0;
}

//************************
gen *makelist(gen * h)
{
 int value;
 gen * curr;
 if(h!=NULL){cout<<"you have a list already"<<endl; return h;}
 int numgen = rand()%6+5;
 for(int i=0; i<numgen; i++)
     {
     value = rand()%41-20;
     if(h==NULL){h=new gen(value); curr=h;}
     else
         {
         curr->makenew(value); curr=curr->getnext();
         }
     }
 return h;
}

void showlist(gen * h)
{
     while(h!=NULL)
     {
     h->display();
     h=h->getnext();
     }
}

gen * bubble(gen * h)
{// we need three temporary pointers
if (h==NULL){cout<<"empty list"<<endl; return NULL;}
gen * prev2;
gen * prev1 = h;
gen * curr = h->getnext();

while(curr!=NULL)
     {

     if(h==prev1 )//make sure you are comparing the first two..
          {     //if first two wrong
          if(prev1->getid()>curr->getid())
               {
               //move the pointers to effect the swap
               h=curr;
               prev1->setnext(curr->getnext());
               curr->setnext(prev1);
               //now...reset the names to start again
               prev1 = h;
               curr=h->getnext();
               }
           else  //FIRST TWO CORRECT!!
               {prev2=prev1;
                prev1=curr;
                curr=curr->getnext();
                }
          }
     else //comparing two in the middle of list somewhere...
          {    
          //if wrong do this..       
          if(prev1->getid()>curr->getid())
                {
                 prev2->setnext(curr);
                 prev1->setnext(curr->getnext());
                 curr->setnext(prev1);
                 //now..start ALL OVER
                 prev1=h;
                 curr=h->getnext();
                }
          //if right..do this
          else{
                prev2=prev1;
                prev1=curr;
                curr=curr->getnext(); //everyone move down one
                }
          }
     
  // cout<<"************** IN SORTER ***********"<<endl;
  //   showlist(h);
  //   system("pause");
     }
return h;
}
