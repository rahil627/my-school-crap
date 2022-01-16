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

litr = students.begin();
while(litr!=students.end() )
    {cout<<*litr<<endl; litr++;}
cout<<endl;

//testing the sort function
students.sort();

litr = students.begin();
while(litr!=students.end() )
    {cout<<*litr<<endl; litr++;}
system("pause");
return 0;
}
