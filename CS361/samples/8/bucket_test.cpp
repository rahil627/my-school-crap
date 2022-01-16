#include <fstream>
#include <iostream>
#include <list>
using namespace std;
#include "bucket.h"

//void show(list<bucket>::iterator start, list<bucket>::iterator stop);

int main()
{
    bucket * bptr;
    list <bucket> blist;
    list <bucket>::iterator bitr;
    list <bucket>::iterator Aitr;
    list <bucket>::iterator Bitr;

    //make a list of buckets
    for(int i=0; i<10; i++)
    {
        bptr=new bucket(i);
        blist.push_back(*bptr);
    }

    Aitr=blist.begin();
    Bitr=blist.end();
    while(Aitr != Bitr){Aitr->display(); Aitr++;}

    system("pause");
    return 0;
}

