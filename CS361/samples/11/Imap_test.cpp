#include <string>
#include <iostream>
#include <map>
using namespace std;
#include "imap.h"

int main()
{
Imap I;
    cout<<I.getsize()<<endl;
    I.addi("john", "caddy");
    I.addi("mary", "ring");
    I.addi("john", "stocks and bonds");
    cout<<I.getsize()<<endl;
    I.show();
    cout<<"zapping john"<<endl;
    I.delperson("john");
    I.show();
    cout<<"back in"<<endl;
    I.addi("john", "caddy");
    I.addi("mary", "ring");
    I.addi("john", "stocks and bonds");
    I.addi("john", "ring");
    I.addi("mary", "house");
    I.show();
    cout<<"deleting rings"<<endl;
    I.delitem("ring");
    I.show();
system("pause");
return 0;
}
