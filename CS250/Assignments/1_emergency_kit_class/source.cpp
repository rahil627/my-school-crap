//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575
//Mr. Ranjan 11:00AM-12:15PM
//A collection of battery objects and a flashlight object are contained
//along with a number of other items in an emergency kit.

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <fstream>
#include <string>
using namespace std;
#include "header.h"

//function prototypes-----------------------------------------------------------------------------
void load(kit * Fk, flashlight * Ff, battery * Fb);//loads data from emerkit.txt
char menu();//character menu
void display(kit * Fk, flashlight * Ff, battery * Fb);//displays entire kit
void change(kit * Fk, flashlight * Ff, battery * Fb);

//main--------------------------------------------------------------------------------------------
int main()
{
	kit * mykit = new kit;
	flashlight * myflashlight = new flashlight;
	battery * mybattery = new battery;
	
	//display(mykit, myflashlight, mybattery);//tests defaults

	load(mykit, myflashlight, mybattery);

	char option = '0'; //a char not in the menu
	while(option != 'E')
		{
		option = menu(); //option comes back from menu
		 switch(option)
			 {
				case 'D':{display(mykit, myflashlight, mybattery);}		break; 
				case 'C':{change(mykit, myflashlight, mybattery);}		break;
		   	    case 'E':{return 0;}									break;
			 }
		}

	system("pause");
	return 0;
}

//functions---------------------------------------------------------------------------------------
void load(kit * Fk, flashlight * Ff, battery * Fb)
{
	 fstream fin;
     fin.open("emerkit.txt",ios::in);
     if (!fin){system("CLS");cout<<"Could not find emerkit.txt!\n";system("pause");cout<<endl;}

	 string x, y, z, e, g, i, j, l;//x, y, z are string-to-bool
	 bool a, c, d;
	 int b, f, h, k;

	 fin>>x>>b>>y>>z>>e>>f>>g>>h;
	 getline(fin, i);
	 fin>>j>>k;
	 getline(fin, l);

	 if(x=="T"){a=true;}
	 else {a=false;}

	 if(y=="T"){c=true;}
	 else {c=false;}

	 if(z=="T"){d=true;}
	 else {d=false;}

	 Fk->set(a, b, c, d);
	 Ff->set(e, f);
	 Fb->set1(g, h, i);
	 Fb->set2(j, k, l);
	 fin.close();
}
char menu()
{
	 system("CLS");
     char option;
	 cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		 <<"**********************************M*E*N*U*******************************\n"
		 <<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
		 <<"(D)isplay items\n\n"
		 <<"(C)hange amount of items\n\n"
		 <<"(E)xit\n\n";
     cin>>option;
   	 cout<<endl;
     option = toupper(option);
     return option;
}
void display(kit * Fk, flashlight * Ff, battery * Fb)
{
	 system("CLS");
	 cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		 <<"***********************************K*I*T********************************\n"
		 <<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n";
	 Fk->display();
	 cout<<endl;
	 Ff->display();
	 cout<<endl;
	 Fb->display();
	 cout<<endl;
	 system("pause");
}
void change(kit * Fk, flashlight * Ff, battery * Fb)
{
	system("CLS");
	char option;
	cout<<"What would you like to change?\n\n"
		<<"(W)ater container\n\n"
		<<"(L)ightsticks\n\n"
		<<"(A)spirin\n\n"
		<<"(B)andaid\n\n"
		<<"(F)lashlight\n\n"
		<<"Battery Set (O)ne\n\n"
		<<"Battery Set (T)wo\n\n";
	cin>>option;
	option=toupper(option);

	system("CLS");
	switch (option)
	{//switch start
	case 'W':{
				bool a;
				cout<<"Should the kit have a water container? (0 for no, 1 for yes)\n";
				cin>>a;
				Fk->setWater(a);
			 } break;
	case 'L':{
				int a;
				cout<<"How many lightsticks should the kit have? (enter an amount)\n";
				cin>>a;
				Fk->setLightstick(a);
			 } break;
	case 'A':{
				bool a;
				cout<<"Should the kit have aspirin? (0 for no, 1 for yes)\n";
				cin>>a;
				Fk->setAspirin(a);
			 } break;
	case 'B':{
				bool a;
				cout<<"Should the kit have a tin of bandaids? (0 for no, 1 for yes)\n";
				cin>>a;
				Fk->setBandaid(a);
			 } break;
	case 'F':
		{
		string a;
		int b, option;
		cout<<"Should the kit have a flashlight? (0 for no, 1 for yes)\n";
		cin>>option;
			if(option==1)
				{	
					cout<<"What kind of battery? (Ex. A AAA D)\n";
					cin>>a;
					cout<<"How many batteries are required? (enter an amount)\n";
					cin>>b;
					Ff->set(a, b);
				}
			else{Ff->setDefault();}
		} break;
	case 'O':
		{
		string a, c;
		int b, option;
		cout<<"Should the kit have a battery set 1? (0 for no, 1 for yes)\n";
		cin>>option;
		if(option==1)
		{
			cout<<"What kind of battery? (Ex. A AAA D)\n";
			cin>>a;
			cout<<"How many batteries? (enter an amount)\n";
			cin>>b;
			cout<<"When do the batteries expire? (dd/mm/yyyy format)\n";
			cin>>c;
			Fb->set1(a, b, c);
		}
		else{Fb->setDefault1();}
		} break;
	case 'T':
		{
		string a, c;
		int b, option;
		cout<<"Should the kit have a battery set 1? (0 for no, 1 for yes)\n";
		cin>>option;
		if(option==1)
		{
			cout<<"What kind of battery? (Ex. A AAA D)\n";
			cin>>a;
			cout<<"How many batteries? (enter an amount)\n";
			cin>>b;
			cout<<"When do the batteries expire? (dd/mmm/yyyy format)\n";
			cin>>c;
			Fb->set2(a, b, c);
		}
		else{Fb->setDefault2();}
		} break;

	}//switch end
}