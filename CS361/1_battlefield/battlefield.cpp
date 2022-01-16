//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575
//Mr. Morris 4:20PM-5:35PM
//To generate a simulation of a distributed sensor network
//compiled with GNU GCC

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <ctime>
#include <cmath>
#include <list>
#include <fstream>
#include <math.h>
#include <iomanip>
using namespace std;
#include "normal.h"
#include "sensor.h"
#include "target.h"
#include "flechette.h"

//functions
char menu()
{
	 system("cls");
     char option;
	 cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
		 <<"**********************************MENU**********************************\n"
		 <<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
		 <<"		A) Report flachettes in 10 timesteps\n"
		 <<"		B) Add a node\n"
		 <<"		C) Delete a node\n"
		 <<"		D) Add an edge\n"
		 <<"		E) Delete an edge\n"
		 <<"		F) \n"
		 <<"		G) Quit\n\n"
		 <<"		Enter a choice: ";
	 cin>>option;
	 cin.ignore();
     option = toupper(option);
     return option;
}

list<flechette> load(list<flechette> flist)
{
    list<flechette>::iterator fitr;
    flechette * fptr;

    list<sensor> slist;
    list<sensor>::iterator sitr;
    sensor * sptr;

    //load flachette list
    int k=0;
    for(int i=0; i<11; i++)
    {
        for(int j=0; j<11; j++)//121 flachettes [(0-100),(0-100)]
        {
            k++;
            fptr = new flechette(k,i,j);
            flist.push_back(*fptr);
        }
    }

    //load sensor list
    for(int i=0; i<750; i++)
    {
        sptr = new sensor(i);
        slist.push_back(*sptr);
        //sptr->display();

        //add sensors into flachettes
        fitr=flist.begin();
        while(fitr!=flist.end())
        {
            if(sptr->getx()>fitr->getx()-2.5&&sptr->gety()>fitr->gety()-2.5&&sptr->getx()<fitr->getx()+2.5&&(sptr->gety()<fitr->gety()+2.5))
            {fitr->addsensor(sptr);}
            fitr++;
        }

    }

    //display sensors
    //sitr=slist.begin();
    //while(sitr!=slist.end()){sitr->display();sitr++;}
    return flist;
}

list<flechette> run(list<flechette> flist)
{
    system("cls");
    list<flechette>::iterator fitr;
    target atarget;
    char type;
    cout<<"What type of noise does this target emit?"<<endl
        <<"options are: T for thermal, A for acoustic, and R for radio"<<endl;
    cin>>type;
    cin.ignore();
    type = toupper(type);
    atarget.gettype(type);
    for(int time=1; time<10001; time++)
    {
        system("cls");
        fitr=flist.begin();
        cout<<"Timestep: "<<time<<endl;
        atarget.display();
        cout<<endl;
        while(fitr!=flist.end())
        {
            //int lastid=fitr->getid();
            //if(fitr->getid()!=lastid)
            fitr->report(atarget);
            fitr++;
        }
        system("pause");
        system("cls");
        char option;
        cout<<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n"
            <<"**********************************MENU**********************************\n"
            <<".:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*.:._.:*~*\n\n"
            <<"		A) Move the target up 5\n"
            <<"		B) Move the target down 5\n"
            <<"		C) Move the target left 5\n"
            <<"		D) Move the target right 5\n"
            <<"		E) Don't Move\n"
            <<"                F) Change noise type\n\n"
            <<"		Enter a choice: ";
        cin>>option;
        cin.ignore();
        option = toupper(option);
        switch(option)
		{
			case 'A':{atarget.move(5,0);}				break;
			case 'B':{atarget.move(-5,0);}	            break;
			case 'C':{atarget.move(0,-5);}              break;
			case 'D':{atarget.move(0,5);}               break;
            case 'E':{;}                                break;
			case 'F':{
                        cout<<"What type of noise does this target emit?"<<endl
                            <<"options are: T for thermal, A for acoustic, and R for radio"<<endl;
                        cin>>type;
                        cin.ignore();
                        type = toupper(type);
                        atarget.gettype(type);;
                    }	                                break;
		 }
        system("pause");
        cout<<endl;
    }
    return flist;
}

//main-------------------------------------------------------------------------------------
int main()
{
    srand(time(NULL));

    list<flechette> flist;
    list<flechette>::iterator fitr;
    flechette * fptr;

    flist=load(flist);
    run(flist);

    //display flachettes
    //fitr=flist.begin();
    //while(fitr!=flist.end()){fitr->display();fitr++;}

    system("pause");
    return 0;
}
