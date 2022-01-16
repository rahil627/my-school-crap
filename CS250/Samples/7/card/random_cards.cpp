#include <iostream>
#include <ctime>
using namespace std;
#include "card.h"

void shuffle(card c[]);

int main()
{
 srand(time(NULL));
 char L = 'A';
 card C[20];
 int R, i;
 for(i=0; i<20; i++)
     {
     C[i].setn(L); L++; C[i].display();}
 shuffle(C);
cout<<endl<<"***********SHUFFLING THE CARDS*******************"<<endl<<endl;
  for(i=0; i<20; i++)
     {if(C[i].getindeck()){C[i].display();}}

//remove the 1st card;
 C[0].remove();
  shuffle(C);
cout<<endl<<"***********SHUFFLING THE CARDS (AGAIN)*******************"<<endl<<endl;
  for(i=0; i<20; i++)
     {if(C[i].getindeck()){C[i].display();}}
 

system("pause");
return 0;
}


void shuffle(card c[])
{
     int R, i;
     for(i=0; i<20; i++)
     {
		 R=rand();
		c[i].setr(R);
     }
     card temp;
     i=0; 
     while(i<19)
     {
		 if(c[i].getr()>c[i+1].getr())//they are wrong
		 {
			 temp = c[i];
             c[i]=c[i+1];
			 c[i+1]=temp;
			 i=0;  //restart? 
         }                
         else{ i++; }
     }
}






