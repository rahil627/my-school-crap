//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <fstream>
#include <string>
#include <ctime>
using namespace std;
#include "card.h"

//function prototypes-----------------------------------------------------------------------------
void load(card chance[], card chest[]);
void shuffle(card c[]);

//main--------------------------------------------------------------------------------------------
int main()
{
	card chance[16];
	card chest[16];

	load(chance, chest);

	int i;//dummy variable

	//chance
	cout<<endl<<"*******************CHANCE*******************"<<endl<<endl;
	//ordered display
	for(i=0; i<16; i++){if(chance[i].getindeck()){chance[i].display();}}
	
	//shuffled
	shuffle(chance);
	cout<<endl<<"*******************SHUFFLING THE CARDS*******************"<<endl<<endl;
	for(i=0; i<16; i++){if(chance[i].getindeck()){chance[i].display();}}

	//remove the 1st card;
	chance[0].remove();
	shuffle(chance);
	cout<<endl<<"*******************SHUFFLING THE CARDS (AGAIN)*******************"<<endl<<endl;
	for(i=0; i<16; i++){if(chance[i].getindeck()){chance[i].display();}}
	
	return 0;
}

//functions---------------------------------------------------------------------------------------
void load(card chance[], card chest[])
{
	fstream fin;
    fin.open("card.txt",ios::in);
	if (!fin){cout<<"Could not find file =(\n";/*return 0;*/}
	
	//card variables
	string n;
	int d=0;//make sure you declare to 0!!! or else d++ makes no sense
	//dummy variable
	int i;

	//chance
	for(i=0; i<16; i++){getline(fin, n); chance[i].setName(n);}
	for(i=0; i<16; i++){chance[i].setID(d); d++;}
	//chest
	for(i=0; i<16; i++){getline(fin, n); chest[i].setName(n);}
	for(i=0; i<16; i++){chest[i].setID(d); d++;}

	//return chance[16];
	//return chest[16];
}
void shuffle(card c[])
{
     int r, i;
	 srand((unsigned)time(0));
     for(i=0; i<16; i++)
     {
		r=rand();
		c[i].setRandom(r);
     }
     card temp;
     i=0; 
     while(i<16)
     {
		 if(c[i].getRandom()>c[i+1].getRandom())//they are wrong
		 {
			 temp = c[i];
             c[i]=c[i+1];
			 c[i+1]=temp;
			 i=0;  //restart? 
         }                
         else{ i++; }
     }
}