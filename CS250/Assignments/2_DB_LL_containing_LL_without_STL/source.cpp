//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575	Partner-Benjamin
//Mr. Ranjan 11:00AM-12:15PM
//To implement a linkable class (personnel) as a part of an employee management system
//for the National Security Agency (NSA).

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <fstream>
#include <string>
using namespace std;//always keep this above #include "file.h"
#include "header.h"

//function prototypes-----------------------------------------------------------------------------
//main functions
char menu();
personnel *load(personnel *h1, skillSet *h2);//loads data from "data.txt"
void display(personnel *h1);//displays agents information
personnel *delAgent(personnel *h1, skillSet *h2);//deletes an agent
personnel *addAgent(personnel *h1, skillSet *h2);//adds an agent to the begining of h1
void searchName(personnel *h1);//searches for a specific agent by name
void searchSkill(personnel *h1, skillSet *h2);//searches for agents with a particular skill
personnel *addSkill(personnel *h1, skillSet *h2);//adds a skill to an existing agent
personnel *sortId(personnel *h1);//sorts agents by ID
personnel *sortSecurity(personnel *h1);//sorts agent by security level
void update(personnel *h1, skillSet *h2);//updates data to "data.txt"

//main--------------------------------------------------------------------------------------------
int main()
{
	personnel *h1=NULL;//set head of personnel link-list to NULL
	skillSet  *h2=NULL;//keep head of skillSet NULL, never return h2 in functions

	//load
	h1=load(h1, h2);

	//menu
	char option = '1'; //a char not in the menu
	while(option != 'I')
	{
		option = menu(); //option comes back from menu
		switch(option)
		{
			case 'A':{display(h1);}						break;
			case 'B':{h1=addAgent(h1, h2);}				break; 
			case 'C':{h1=delAgent(h1, h2);}				break;
			case 'D':{searchName(h1);}					break;
			case 'E':{searchSkill(h1, h2);}				break; 
			case 'F':{h1=addSkill(h1, h2);}				break; 
			case 'G':{h1=sortId(h1);}					break; 
			case 'H':{h1=sortSecurity(h1);}				break; 
			case 'I':{update(h1, h2);}					break;
		 }
	}
	cout<<endl;
	return 0;
}

//functions---------------------------------------------------------------------------------------
char menu()
{
	 system("cls");
     char option;
	 cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		 <<"**********************NSA AGENT MANAGEMENT SYSTEM***********************\n"
		 <<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
		 <<"		A) Display personnel\n"
		 <<"		B) Add a new agent\n"
		 <<"		C) Delete a deceased agent\n"
		 <<"		D) Search for a specific agent by name\n"
		 <<"		E) Search for all agents with a particular skill\n"
		 <<"		F) Add a skill to an existing agent\n"
		 <<"		G) Sort all agents by id number\n"
		 <<"		H) Sort all agents by security level\n"
		 <<"		I) Quit\n\n"
		 <<"		Enter a choice: ";
	 cin>>option;
	 cin.ignore();
     option = toupper(option);
     return option;
}
personnel *load(personnel *h1, skillSet *h2)
{
	fstream fin;
    fin.open("data.txt",ios::in);
	if (!fin){cout<<"Could not find file =(\n";return 0;}

	personnel *c1;
	skillSet *c2;

	string L, F, s;
	int I, S;
	char c;
	
	while(!fin.eof())
	{
		if(h1==NULL)//sets head
		{
			fin>>L>>F>>I>>S;
			
			//skillSet link-list
			fin.ignore();
			c=fin.peek();
			while (c!='*')
			{
				if(h2==NULL)
					{
					getline(fin, s);
					h2=new skillSet(s);
					c2=h2;
					c=fin.peek();
					}
				else
				{
					getline(fin, s);
					c2->makeNew(s);
					c2=c2->getNext();
					c=fin.peek();
				}
			}
			getline(fin, s);//just to read past "*", using s again-doesn't matter

			h1=new personnel(L, F, I, S, h2);
			c1=h1;
			
		}
		else//sets next
		{
			fin>>L>>F>>I>>S;
			
			//skillSet link-list
			h2=NULL;//i'm using the same pointer again, so reset it
			fin.ignore();
			c=fin.peek();
			while (c!='*')
			{
				if(h2==NULL)
					{
					getline(fin, s);
					h2=new skillSet(s);
					c2=h2;
					c=fin.peek();
					}
				else
				{
					getline(fin, s);
					c2->makeNew(s);
					c2=c2->getNext();
					c=fin.peek();
				}
			}
			getline(fin, s);//just to read past "*", using s again-doesn't matter
			c1->makeNew(L, F, I, S, h2);
			c1=c1->getNext();
		}
	}
	fin.close();
	return h1;
}
personnel * addAgent(personnel *h1, skillSet *h2)
{
	string L, F, s;
	int I, S;
	personnel *c1=h1;
	skillSet *c2=NULL;

	system("cls");
	cout<<"Enter the agent's last name: ";
	cin>>L;
	cout<<"Enter the agent's first name: ";
	cin>>F;
	cout<<"Enter the agent's ID: ";
	cin>>I;
	cout<<"(type an integer: 1=Normal, 2=High, 3=Secret, 4=Top Secret)\n"
		<<"Enter the agent's security level: ";
	cin>>S;

	int option;
	while(option!=12)
	{

		system("cls");
		cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
			<<"*******************************Skill List*******************************\n"
			<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
			<<"		1)  Espionage\n"
			<<"		2)  Assassination\n"
			<<"		3)  Infiltration\n"
			<<"		4)  Electronics\n"
			<<"		5)  Computer Warfare\n"
			<<"		6)  Defensive Driving\n"
			<<"		7)  Hand-to-hand Combat\n"
			<<"		8)  Small Arms\n"
			<<"		9)  Automatic Weapons\n"
			<<"		10) Bomb Squad\n"
			<<"		11) Custom\n"
			<<"		12) Quit\n\n"
			<<"		Choose a skill to add: ";
		cin>>option;
		switch(option)
		{
			case 1: {s="Espionage";}			break;
			case 2: {s="Assassination";}		break;
			case 3: {s="Infiltration";}			break;
			case 4: {s="Electronics";}			break;
			case 5: {s="Computer Warfare";}		break;
			case 6: {s="Defensive Driving";}	break;
			case 7: {s="Hand-to-hand Combat";}	break;
			case 8: {s="Small Arms";}			break;
			case 9: {s="Automatic Weapons";}	break;
			case 10:{s="Bomb Squad";}			break;
			case 11:		
				{
					cout<<"		Enter a custom skill (press enter twice): ";
					cin.ignore();//skips the \n from the cin>>S
					getline(cin, s);//still have to press enter twice
				} break;
		}
		
		if(option==12){/*skip this*/;}
		else
		{
			if(h2==NULL)
				{
					h2=new skillSet(s);
					c2=h2;
				}
			else
				{
					c2->makeNew(s);
					c2=c2->getNext();
				}
		}
	}
	personnel * newPersonnel;
	newPersonnel=new personnel(L, F, I, S, h2);
	if(h1=NULL)
	{
		h1=newPersonnel;
	}
	else
	{
		newPersonnel->setNext(c1);//why couldn't I have used h1 here?
		h1=newPersonnel;
	}
	return h1;
}
personnel * delAgent(personnel *h1, skillSet *h2)
{
 system("cls");
 personnel * target;
 personnel * prev;
 string value;
 cout<<"Enter the agents last name: ";
 cin>>value;
 cout<<endl;

 if(h1->getLastName()==value)
 {
	 target = h1;
	 h1= h1->getNext();
	 target->setNext(NULL);
	 delete target;
	 system("pause");
 }
 else
 {
	 target=h1->getNext();
	 prev=h1;
	 while(target->getLastName()!=value)
	 {
		 prev = target;
		 target=target->getNext();
		 if(target==NULL)
		 {
			 cout<<"Sorry that personnel does not exist\n"<<endl; 
			 system("pause"); 
			 return h1;
		 }
	 }
	 prev->setNext(target->getNext());
	 target->setNext(NULL);
	 delete target;
	 cout<<endl<<endl;
	 system("pause");
    }
return h1;
}
void searchName(personnel *h1)
{
	string n;
	personnel *c1;
	c1=h1;
	system("cls");
	cout<<"Enter the agent's last name: ";
	cin>>n;
	cout<<endl;
    while(h1!=NULL)
    {
		if(h1->getLastName()==n){h1->display();}
		h1=h1->getNext();
	}
    cout<<endl;
    system("pause");
}
void searchSkill(personnel *h1, skillSet *h2)
{
	system("cls");
	string s;

	cout<<"Enter a skill (press enter twice): ";
	getline(cin, s);//have to press enter twice
	cin.ignore();

	while(h1!=NULL)//if h1 is NULL, it will skip this
	{
		h2=h1->getPSkillSet();
		while(h2!=NULL)//if h2 is NULL, it will skip this
		{
			if (h2->getSkill()==s){h1->display();}
			h2=h2->getNext();
		}
		h1=h1->getNext();
	}
    cout<<endl;
    system("pause");
}
personnel * addSkill(personnel *h1, skillSet *h2)
{
	string n, s;
	int option;

	system("cls");
	cout<<"Enter the agent's last name: ";
	cin>>n;
	cin.ignore();//skips the \n from the cin>>S
	
	system("cls");
	cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		<<"*******************************Skill List*******************************\n"
		<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
		<<"		1)  Espionage\n"
		<<"		2)  Assassination\n"
		<<"		3)  Infiltration\n"
		<<"		4)  Electronics\n"
		<<"		5)  Computer Warfare\n"
		<<"		6)  Defensive Driving\n"
		<<"		7)  Hand-to-hand Combat\n"
		<<"		8)  Small Arms\n"
		<<"		9)  Automatic Weapons\n"
		<<"		10) Bomb Squad\n"
		<<"		11) Custom\n\n"
		<<"		Choose a skill to add: ";
		cin>>option;
		cout<<endl;
		switch(option)
		{
			case 1: {s="Espionage";}			break;
			case 2: {s="Assassination";}		break;
			case 3: {s="Infiltration";}			break;
			case 4: {s="Electronics";}			break;
			case 5: {s="Computer Warfare";}		break;
			case 6: {s="Defensive Driving";}	break;
			case 7: {s="Hand-to-hand Combat";}	break;
			case 8: {s="Small Arms";}			break;
			case 9: {s="Automatic Weapons";}	break;
			case 10:{s="Bomb Squad";}			break;
			case 11:		
				{
					cout<<"		Enter a custom skill (press enter twice): ";
					getline(cin, s);//still have to press enter twice
				} break;
		}
	
	if(h1==NULL){cout<<"Personnel list is empty!";}//why would anybody do this...it's addSkill!
	else
	{
		personnel *c1=h1;

		while(c1!=NULL)//it will keep going through list, it will get the last match, so having 2 same last names is confusing
		{
			if(c1->getLastName()==n)
			{
				h2=c1->getPSkillSet();
				skillSet *newSkillSet;
				newSkillSet=new skillSet(s);
				if(h2==NULL)
				{
					c1->setPSkillSet(newSkillSet);
				}
				else
				{
					newSkillSet->setNext(h2);//starts with newSkillSet, sets h2 after it
					c1->setPSkillSet(newSkillSet);
				}
			}
			c1=c1->getNext();
		}
	}
	return h1;
}
personnel * sortId(personnel *h1)
{
	personnel * c = h1->getNext(), *prev2, *prev1 = h1;
	int holder = 0;

	system("cls");
	while (c != NULL) {
		if (h1 == prev1) 
		{
			if (prev1->getId() > c->getId()) 
			{
				h1 = c;
				prev1->setNext(c->getNext());
				c->setNext(prev1);
				prev1 = h1;
				c = h1->getNext();
			}
			else 
			{
				prev2 = prev1;
				prev1 = c;
				c = c->getNext();
			}
		}
		else 
		{
			if (prev1->getId() > c->getId()) 
			{
				prev2->setNext(c);
				prev1->setNext(c->getNext());
				c->setNext(prev1);
				prev1 = h1;
				c = h1->getNext();
			}
			else 
			{
				prev2 = prev1;
				prev1 = c;
				c = c->getNext();
			}
		}
	}

	cout<<"Personnel sorted by ID successfully.\n\n";
	system("pause");

	return h1;
}
personnel * sortSecurity(personnel *h1)
{
	system("cls");

	personnel * c = h1->getNext(), *prev2, *prev1 = h1;
	int holder = 0;

	while (c != NULL) 
	{
		if (h1 == prev1) 
		{
			if (prev1->getSecurityLevel() > c->getSecurityLevel()) 
			{
				h1 = c;
				prev1->setNext(c->getNext());
				c->setNext(prev1);
				prev1 = h1;
				c = h1->getNext();
			}
			else 
			{
				prev2 = prev1;
				prev1 = c;
				c = c->getNext();
			}
		}
		else 
		{
			if (prev1->getSecurityLevel() > c->getSecurityLevel()) 
			{
				prev2->setNext(c);
				prev1->setNext(c->getNext());
				c->setNext(prev1);
				prev1 = h1;
				c = h1->getNext();
			}
			else 
			{
				prev2 = prev1;
				prev1 = c;
				c = c->getNext();
			}
		}
	}

	cout<<"Personnel sorted by ID successfully.\n\n";
	system("pause");
	return h1;
}
void display(personnel *h1)
{    
	system("cls");
    if(h1==NULL){cout<<"Personnel list is empty!"<<endl;}
    else
	{
		while(h1!=NULL)
		{
			h1->display();
			h1=h1->getNext();
		}
	}
	system("pause");
}
void update(personnel *h1, skillSet *h2)
{
	system("CLS");
	personnel * c1;//why do i need to use c1? I'm not returning
    c1=h1;
	fstream fout;
	fout.open("data.txt",ios::out);
	while (c1!=NULL)//if c1 is NULL, it will skip this
	{
		fout<< c1->getLastName()		<<endl
			<< c1->getFirstName()		<<endl
			<< c1->getId()				<<endl
			<< c1->getSecurityLevel()	<<endl;
			h2=c1->getPSkillSet();
		while(h2!=NULL)//if h2 is NULL, it will skip this
		{
			fout<<h2->getSkill()<<endl;
			h2=h2->getNext();
		}
		if(c1->getNext()!=NULL){fout<<"*\n";}
		else{fout<<"*";}//won't put an endl on the last *
		c1=c1->getNext();
	}
	fout.close();
	cout<<"Updated Successfully\n";
}