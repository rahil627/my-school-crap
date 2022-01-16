#include<iostream>
#include<string>
#include<fstream>
using namespace std;

#include "team.h"

int main()
{
team *t=NULL;
team *curr;
string stemp; //temporary string
fstream fin;
fin.open("team_data.txt",ios::in);
getline(fin,stemp);
while(!fin.eof())
{
    
    if(t==NULL)
        {t=new team;
         t->setcoach(stemp);
         curr=t;
         //cout<<"first complete"<<endl;
         }
    else
        {
        curr->makenew();
        curr=curr->getnext();
        curr->setcoach(stemp);
        //cout<<"another"<<endl;
        }
getline(fin,stemp);
}

curr = t;
while(curr!=NULL)
       {

       curr->display();
       curr=curr->getnext();
       }


system("pause");
return 0;}
