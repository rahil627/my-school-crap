//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <fstream>
#include <string>
using namespace std;

#include "property.h"

//function prototypes-----------------------------------------------------------------------------
property*load(property*head);
void display(property*head);

//main--------------------------------------------------------------------------------------------
int main()
{
	property*head=NULL;//board (all properties)
	head=load(head);

	display(head);

	return 0;
}

//functions---------------------------------------------------------------------------------------
void display(property*head)
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