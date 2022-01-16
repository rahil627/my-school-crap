//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575
//Mr. Morris 4:20PM-5:35PM
//To generate a simulation of a distributed sensor network

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <string>
#include <map>
#include <list>
#include <cmath>
#include <cstdlib>
#include <ctime>
#include <iomanip>
using namespace std;
#include "header.h"

//functions---------------------------------------------------------------------------------------
char menu()
{
	 system("cls");
     char option;
	 cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		 <<"**********************************MENU**********************************\n"
		 <<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
		 <<"		A) Display map\n"
		 <<"		B) Add an edge\n"
		 <<"		C) Delete an edge\n"
		 <<"		D) Display matrix\n"
		 <<"		E) Quit\n\n"
		 <<"		Enter a choice: ";
	 cin>>option;
	 cin.ignore();
     option = toupper(option);
     return option;
}
universe generate(universe u)
{
    srand((unsigned)time(0));
    int n; char s; char d; int w; char r; bool b;
    cout<<"Enter the number of nodes: "; cin>>n;
    cout<<"Generating universe...\n";
    edgelist tedgelist;//t=temporary
    for(int i=0; i<n; i++)//initialize
    {
        s='A'+i;
        u.addnmap(s,tedgelist);
    }
    for(int i=0; i<(n+(rand()%((n^2)+1))); i++)//randomize add [n,(n^2)] nodes
    {
        r=rand()%n;
        s='A'+r;//random start
        r=rand()%n;
        d='A'+r;//random dest
        w=(rand()%10)+1;//random weight [1-10]
        if(s!=d||u.checkedge(s,d)!=1){u.addedge(s,d,w);}
    }
    return u;
}
void display(universe u)
{
    system("cls");
    cout<<"There are "<<u.getsize()<<" nodes in the map\n";
    u.display();
    system("pause");
}
universe addedge(universe u)
{
    char s; char d; int w;
    cout<<"\nEnter a char for the starting node: "; cin>>s; s = toupper(s);
    cout<<"Enter a char for the destinated node: "; cin>>d; d = toupper(d);
    cout<<"Enter an int for the weight: "; cin>>w; w = toupper(w);
    if(s!=d||u.checkedge(s,d)!=1){u.addedge(s,d,w);}
    return u;
}
universe deledge(universe u)
{
    char s; char d;
    cout<<"\nEnter a char for the starting node: "; cin>>s; s = toupper(s);
    cout<<"Enter a char for the destinated node: "; cin>>d; d = toupper(d);
    u.deledge(s,d);
    return u;
}
void displaymatrix(universe u)
{
    system("cls");
    int n=u.getsize();
    u.matrix(n);
    system("pause");
}
//main-------------------------------------------------------------------------------------
int main()
{
    universe pubuniverse;
    pubuniverse=generate(pubuniverse);
    //menu
    char option = '1'; //a char not in the menu
	while(option != 'G')
	{
		option = menu(); //option comes back from menu
		switch(option)
		{
			case 'A':{display(pubuniverse);}				break;
			case 'B':{pubuniverse=addedge(pubuniverse);}    break;
			case 'C':{pubuniverse=deledge(pubuniverse);}	break;
			case 'D':{displaymatrix(pubuniverse);}			break;
			case 'E':{return 0;}				           	break;
		 }
	}
	cout<<endl;


}
