#include <string>
#include <iostream>
#include <list>

using namespace std;
#include "prof.h"
int main()
{
prof * temp;
list <prof> faculty;
for(int i=0; i<10; i++)
            {
            temp=new prof;
            faculty.push_front(*temp);
            }


list <prof>::iterator pitr;
pitr=faculty.begin();
while(pitr!=faculty.end())
    {
    pitr->display();
    pitr++;
    }

system("pause");
return 0;
}