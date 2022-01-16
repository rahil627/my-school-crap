#include <iostream>
#include <list>
#include <string>
#include <fstream>
using namespace std;

int main()
{
list <string> roster;
string n;

if(roster.empty()){cout<<"sorry no names"<<endl;}
/*for(int i=0; i<5; i++)
    {
    cout<<"enter a name"<<endl;
    getline(cin,n);
    roster.push_back(n);
    }
*/
fstream fin;
fin.open("roster.txt",ios::in);
while(!fin.eof())
    {getline(fin,n); roster.push_front(n);}

roster.sort();

list <string>::iterator ritr;
ritr=roster.begin();
cout<<endl<<"after loading"<<endl<<endl;
int i=roster.size();
cout<<"you have "<<i<<" members"<<endl;
while(ritr!=roster.end())
    {cout<<*ritr<<endl;  ritr++;}


roster.unique();
i=roster.size();
cout<<"you have "<<i<<" members"<<endl;
ritr=roster.begin(); //DONT FORGET TO START OVER!
while(ritr!=roster.end())
    {cout<<*ritr<<endl;  ritr++;}
cout<<endl<<"reversed"<<endl;
roster.reverse();
ritr=roster.begin(); //DONT FORGET TO START OVER!
while(ritr!=roster.end())
    {cout<<*ritr<<endl;  ritr++;}


cout<<"testing pop front"<<endl<<endl;
roster.pop_front();
ritr=roster.begin(); //DONT FORGET TO START OVER!
while(ritr!=roster.end())
    {cout<<*ritr<<endl;  ritr++;}


ritr=roster.begin(); ritr++; ritr++;

roster.insert(ritr, "a, a");
roster.remove("barley rye");
cout<<endl<<endl<<"testing insert at itr"<<endl;
ritr=roster.begin(); //DONT FORGET TO START OVER!
while(ritr!=roster.end())
    {cout<<*ritr<<endl;  ritr++;}
system("pause");
return 0;
}






