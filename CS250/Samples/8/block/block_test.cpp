#include <iostream>
#include <fstream>
using namespace std;

#include "block.h"

void display(block * B[]);

int main()
{
   block * B[9];
   int tempi;

   fstream fin;
   fin.open("block_data.txt",ios::in);
   
   for(int i=0; i<9; i++)
       {
       fin>>tempi;
       B[i]= new block(tempi);
       }
   display(B);
system("pause");
return 0;
}

void display(block * B[])
     {
     for(int i=0; i<9; i++)
        {B[i]->display();}
     }
