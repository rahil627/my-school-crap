#include <string>
#include <iostream>
using namespace std;
#include "assign1.h"

int main()
{   

    tractor T1, T2;

    T1.setmake("Massey Furguson");
    T1.setmodel(8300);
    T1.sethp(320);
    T1.setpto(225);
    T1.setbrakes(true);
    T1.setdozer(true);
    T1.setblade(true);
    T1.setloader(true);
    T1.display();

    T2.setmake("Ford");
    T2.setmodel(100);
    T2.sethp(110);
    T2.setpto(80);
    T2.setdigger(true);
    T2.display();

system("pause");
return 0;
}
