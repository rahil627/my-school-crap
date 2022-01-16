#include <iostream>
#include <fstream>
#include <string>
using namespace std;

#include "student.h"

void showall(student f[]);
void load(student f[]);

int main()
{

 student freshmen[10];
 load(freshmen);
 showall(freshmen);

system("pause");
return 0;
}

void showall(student f[])
{
for (int i=0; i<10; i++)
    {
    f[i].display();
    }
}

void load(student f[])
     {
     fstream fin;
     fin.open("students.txt",ios::in);
     string n, i, m;
     double t;

     int j=0;
     getline(fin,n);  //seed read to start things off.
     while(!fin.eof())
     {
     getline(fin,i);
     fin>>t;
     fin.ignore();
     getline(fin,m);
     f[j].set(n,i,t, m);
     j++;
     getline(fin,n);
     }
fin.close();
}

