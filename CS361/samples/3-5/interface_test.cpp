#include <iostream>
#include <cmath>
#include <ctime>
#include <list>

using namespace std;
#include "normal.h"
#include "sensor2.h"
#include "flechette.h"

int main()
{
    flechette F1(100,50,50);
    sensor S1(1,'V');

    for(int i=0; i<25; i++)
    F1.getsig(S1);



system("pause");
return 0;
}