#include <string>
#include <iostream>
#include <map>
using namespace std;
#include "imap.h"

int main()
{
    Imap I;
    cout<<I.getsize()<<endl;
    //I.insert( pair<string,int>("Bob Smith",1) );
    weight 1;
    I.addi("john", weight);
    I.addi("mary", 11);
    I.addi("john", 2);
    cout<<I.getsize()<<endl;
    I.show();
    system("pause");

    cout<<endl<<"zapping john"<<endl;
    I.delperson("john");
    I.show();
    system("pause");

    cout<<endl<<"back in"<<endl;
    I.addi("john", 3);
    I.addi("mary", 12);
    I.addi("john", 4);
    I.addi("john", 5);
    I.addi("mary", 13);
    I.show();
    system("pause");
/*
    cout<<endl<<"deleting rings"<<endl;
    I.delitem("ring");
    I.show();
    system("pause");
*/
return 0;
}
