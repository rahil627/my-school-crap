#include <iostream>
#include <fstream>
using namespace std;

#include "wrench.h"

wrench * load(wrench * head);
void showallw(wrench * head);

int main()
{
 wrench * head = NULL;
 head = load(head);
 showallw(head);
    
system("pause");
return 0;
}
//***************************************
wrench * load(wrench * head)
       {
       tool * curr;
       double tempp, temps;
       double tempb;
       wrench * tempw;

       fstream fileIn;
       fileIn.open("wrench.dat",ios::in);
       fileIn>>tempp;//perform a seed read
       while(!fileIn.eof())
           {

           fileIn>>tempb;
           fileIn>>temps;
           if(head==NULL)//first wrench
                {
                head = new wrench;
                head->setp(tempp);
                head->setbe(tempb);
                head->sets(temps);
                curr=head; //WHY IS THIS OKAY?????
                }
           else
                {
                tempw=new wrench;
                tempw->setp(tempp);
                tempw->setbe(tempb); 
                tempw->sets(temps);
                curr->setnext(tempw);
                curr=curr->getnext();
                }
           fileIn>>tempp;  //are there any more???
           }
       fileIn.close();
       return head; 
       }

void showallw(wrench * head)
     {
           while(head!=NULL)
           {
           head->display();
           head = (wrench *)head->getnext();  //explicit cast
           }
     }
