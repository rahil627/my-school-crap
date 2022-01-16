#include <iostream>
#include <string>
#include <fstream>
using namespace std;

#include "assign2.h"
int menu();

int main()
{
 int itemp;
 string stemp;
 int choice=99;
 element * Eptr = NULL;
 isotope * Iptr = NULL;
 
 while(choice!=4)
                 {choice = menu();
                   switch(choice)
                   {
                   case 1:
                        {cin.ignore();
                         cout<<"name?"<<endl;
                         getline(cin,stemp);
                         cout<<"atomic number?"<<endl;
                         cin>>itemp;
                         Eptr=new element(stemp, itemp);
                         //Eptr->display();
                         if(Iptr!=NULL){delete Iptr; Iptr=NULL;}
                         }break; //end of case 1
                   case 2:
                        {
                        if(Eptr==NULL){cout<<"No element to modify"<<endl; break;}
                        else{
                             Iptr = new isotope(Eptr, "deuterium", 1);
                             //Iptr->display();
                             delete Eptr;
                             Eptr=NULL;
                             }
                        }break;//end of case 2
                    case 3:
                       {
                       if (Eptr!=NULL){Eptr->display();}
                       if(Iptr!=NULL){Iptr->display();}
                       }break;//end of case 3

                   }//end of the switch
                   }//end of while


 

system("pause");
return 0;
}



int menu()
{int choice;
     cout<<"1  create new element "<<endl;
     cout<<"2  modify an element into a isotope"<<endl;
     cout<<"3  display the current isotope/element"<<endl;
     cout<<"4  exit"<<endl;
     cin>>choice;
     return choice;
}

