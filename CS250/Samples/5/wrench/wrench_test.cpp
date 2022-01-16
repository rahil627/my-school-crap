#include <iostream>
#include <fstream>
using namespace std;

#include "wrench.h"

int main()
{
    tool T1;
    T1.setp(3.24);
    T1.display();

    wrench W1;
    W1.setp(1.99);
    W1.setbe(true);
    W1.sets(7.5);
    W1.display();

system("pause");
return 0;
}
