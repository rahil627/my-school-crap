#include <iostream>
#include <stack>  //here is the LL lib.
#include <string>
using namespace std;

int main()
{
//Lets Make A LINK LIST!!

stack <string> students;


students.push("Billy Bob Thornton");
students.push("Angelina Jolie");
students.push("Rachael Weiss");
students.push("Mel Gibson");

cout<<"initial student list"<<endl;


while(!students.empty() )
    {cout<<students.top()<<endl;
     students.pop(); 
    }
cout<<endl;

system("pause");
return 0;
}