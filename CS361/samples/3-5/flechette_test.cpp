#include <iostream>
#include <ctime>
#include <cmath>
#include <list>
#include <fstream>
using namespace std;
#include "normal.h"
#include "sensor.h"
#include "flechette.h"

int main()
{
    fstream fout;
    srand(time(NULL));

    list<flechette> Flist;
    list<flechette>::iterator fitr;
    flechette * fptr;

    list<sensor> Slist;
    list<sensor>::iterator sitr;
    sensor * sptr;

    int k=0;
    for(int i=0; i<8; i++)
      { for(int j=0; j<8; j++)
         {  k++;
            fptr = new flechette(k,i,j);
            Flist.push_back(*fptr);
        }
    }

    fitr = Flist.begin();
    fout.open("flechette.txt",ios::out);
    while(fitr!=Flist.end())
       {fout<<fitr->getx()<<" "<<fitr->gety()<<endl;
        fitr->display();
        fitr++;
        }

fout.close();


    for(int i=0; i<1000; i++)
      {
            sptr = new sensor(i, 'T');
            sptr->settemp(24.6);
            Slist.push_back(*sptr);
        }


    sitr = Slist.begin();
    fout.open("sensor.txt",ios::out);
    while(sitr!=Slist.end())
       {fout<<sitr->getx()<<" "<<sitr->gety()<<endl;
        sitr++;
        }

fout.close();
int count=0;

double sig;
    sitr=Slist.begin();
for(int time=0; time<10; time++)
    {cout<<"      TIME: "<<time<<endl;
    while(sitr!=Slist.end())
       {sig=sitr->sendsig();

        if(sig<1000){sitr->display(); }
        sitr++;
        }

     sitr=Slist.begin();

     system("pause");

    }

system("pause");
return 0;
}
