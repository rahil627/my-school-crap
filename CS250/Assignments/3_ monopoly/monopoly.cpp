//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575
//Mr. Ranjan 11:00AM-12:15PM
//to create a simulation of monopoly

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <fstream>
#include <string>
#include <ctime>
using namespace std;
#include "dice.h"
#include "card.h"
#include "property.h"
#include "player.h"


//function prototypes-----------------------------------------------------------------------------
void load();
int menu();
property*load(property*head);
void propertyDisplay(property*head);
void cardLoad(card chance[], card chest[]);
void cardShuffle(card c[]);
//main--------------------------------------------------------------------------------------------
int main()
{
	property*head=NULL;//board (all properties)
	head=load(head);

	card chance[16];
	card chest[16];

	cardLoad(chance, chest);

	string n, t;

	cout<<"Welcome to Monopoly\n"
		<<"Enter your name: "; 
	cin>>n;
	

	system("cls");
	int option;
	cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		<<"*********************************Tokens*********************************\n"
		<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
		<<"		1)  Race Car\n"
		<<"		2)  Dog\n"
		<<"		3)  Cannon\n"
		<<"		4)  Top Hat\n"
		<<"		5)  Battleship\n"
		<<"		6)  Horse & Rider\n"
		<<"		7)  Shoe\n"
		<<"		8)  Thimble\n"
		<<"		9)  Iron\n"
		<<"		10) Wheelbarrow\n"
		<<"		11) Sack of Money\n\n"
		<<"		Choose your token: ";
		cin>>option;
		switch(option)
		{
			case 1: {t="Race Car";}			break;
			case 2: {t="Dog";}				break;
			case 3: {t="Cannon";}			break;
			case 4: {t="Top Hat";}			break;
			case 5: {t="Battleship";}		break;
			case 6: {t="Horse & Rider";}	break;
			case 7: {t="Shoe";}				break;
			case 8: {t="Thimble";}			break;
			case 9: {t="Iron";}				break;
			case 10:{t="Wheelbarrow";}		break;
			case 11:{t="Sack of Money";}	break;
		}
	player player1;
	player1.set(n, t);
	
	//dice_test
	dice d;
	//srand((unsigned)time(0));
	srand(time(NULL));

	option=0;
	while(option!=2)
	{
		option=menu();

		system("cls");
		switch(option)
		{
			case 1: {
					d.roll();
					if(d.getR1()==d.getR2()){d.doubles();d.display();}
					else{d.display();}
					}	break;
			case 2:{;}	break;
		}
		system("pause");
	}//while
	return 0;
	
}//main

//functions---------------------------------------------------------------------------------------
int menu()
{
	int option;
	system("cls");
	cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		<<"**********************************Menu**********************************\n"
		<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
		<<"		1) Roll Dice\n"
		<<"		2) Quit Game\n\n"

		<<"		You are on display\n" 
		<<"		What do you want to do?: ";
		cin>>option;
		return option;
}
property*load(property*head)
{
	fstream fin;
    fin.open("property.txt",ios::in);
	if (!fin){cout<<"Could not find file =(\n";return 0;}


	property*sList;//special
	residential*dList;
	//cList*card;
	railroad*rList;
	utility*uList;

	//property (includes special)
	string n;
	int p0=0;
	//ownable (includes raiload & utility)
	int p, r0, v;
	//residential
	int r1, r2, r3, r4, r5, c1;
	string c;
	
	string s;
	char type;

	fin>>type;
	fin.ignore();
	while(!fin.eof())
	{
       switch(type)
       {
		   	case 'S'://special (i changed the C's to S's...for now)
			{   
				//fin.ignore();
				getline(fin,n);
				sList = new property(n, p0);
				if(head==NULL){head=sList;}
				else{sList->setNext(head);head=sList;}//using setNext will make the list backwards
			}break;
			/*//i made this special for now--edited data file
		   	case 'C'://card
			{   
				fin.ignore();
				getline(fin,s);
				fptr = new F(s);
				if(head==NULL){head = sList;}
				else{sList->setnext(head);head = sList;}
			}break;*/
			case 'D'://residential (i added this in the data file)
			{
				//fin.ignore();
				getline(fin,n);
				getline(fin,c);
				fin>>p>>c1>>r0>>r1>>r2>>r3>>r4>>r5>>v;
				dList = new residential(n, p0, c, p, c1, r0, r1, r2, r3, r4, r5, v);
				if(head==NULL){head=dList;}
				else{dList->setNext(head);head=dList;}
			}break;
            case 'R'://railroad
			{
				//fin.ignore();
				getline(fin,n);
				rList = new railroad(n, p0);
				if(head==NULL){head=rList;}
				else{rList->setNext(head);head=rList;}
			}break;
			case 'U'://utility
			{
				//fin.ignore();
				getline(fin,n);
				uList = new utility(n, p0);
				if(head==NULL){head=uList;}
				else{uList->setNext(head);head=uList;}		
			}break;
                        
		}
       fin>>type;
	   fin.ignore();
	   p0++;
    }
	fin.close();
	return head;
}
void propertyDisplay(property*head)
{
	property*sList;//special
	residential*dList;
	//cList*card;
	railroad*rList;
	utility*uList;

	char type;

    while(head!=NULL)
	{
		type=head->getType();//ends up crashing here
        switch(type)
		{
			case 'S': {sList = (property*)head;
                        sList->display();
                        }break;
            case 'D': {dList = (residential*)head;
                        dList ->display();
                        }break;
            case 'R': {rList = (railroad*)head;
                        rList->display();
                        }break;
            case 'U': {uList = (utility*)head;
                        uList->display();
						}break;
		}
        head=head->getNext();//head does = NULL at the end!
	}//but it somehow keeps looping...
}
void cardLoad(card chance[], card chest[])
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
void cardShuffle(card c[])
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
