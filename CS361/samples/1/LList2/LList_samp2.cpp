#include <iostream>
#include <list>  //here is the LL lib.
#include <string>
using namespace std;

int main()
{
//Lets Make A LINK LIST!!

list <string> students;
list <string>::iterator litr;

students.push_front("Billy Bob Thornton");
students.push_front("Angelina Jolie");
students.push_front("Rachael Weiss");
students.push_back("Mel Gibson");

cout<<"initial student list"<<endl;

litr = students.begin();
while(litr!=students.end() )
    {cout<<*litr<<endl; litr++;}
cout<<endl;

//test the insert function
litr=students.begin();
while((*litr!="Angelina Jolie")&&(litr!=students.end()))
    {litr++;}
students.insert(litr,"Jay Morris");

cout<<"display after insertion"<<endl;
int num = students.size();
cout<<"there are "<<num<<" students"<<endl;
litr = students.begin();
while(litr!=students.end() )
    {cout<<*litr<<endl; litr++;}

//testing the reverse display
cout<<endl<<endl;
litr=students.end();
while(litr!=students.begin() )
    {litr--;cout<<*litr<<endl; }

//testing erase

litr=students.begin();
while((*litr!="Angelina Jolie")&&(litr!=students.end()))
    {litr++;}
cout<<endl<<endl;
students.erase(litr);
litr = students.begin();
while(litr!=students.end() )
    {cout<<*litr<<endl; litr++;}

//popping the front of the list
cout<<endl<<endl;
students.pop_back();
litr = students.begin();
while(litr!=students.end() )
    {cout<<*litr<<endl; litr++;}
system("pause");
return 0;
}
