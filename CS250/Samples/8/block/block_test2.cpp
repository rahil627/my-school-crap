#include <iostream>
#include <fstream>
using namespace std;

#include "block2.h"

void display(block * B[]);

int main()
{
   block * B[9];
   int temp1, temp2, temp3;
   char t; 
   int i;
   fstream fin;
   fin.open("block_data2.txt",ios::in);

   for (i=0; i<9; i++){B[i]=NULL;}
   
   for(i=0; i<9; i++)
       {fin>>t;
        if(t=='b'){fin>>temp1;
                   B[i]=new block(temp1);}
        if(t=='r'){fin>>temp1>>temp2>>temp3;
                   B[i]=new rectangle(temp1, temp2, temp3);
                   }
        }

   display(B);
system("pause");
return 0;
}

void display(block * B[])
     {
     int i;
     rectangle * rptr; 
     char tempc;
     for(i=0; i<9; i++)
        {
        if(B[i]!=NULL){tempc=B[i]->gettype();
                       if(tempc=='b'){B[i]->display();}
                       if(tempc=='r'){
                       rptr = (rectangle *)B[i];
                       rptr->display();}
                       }
        }
     }
