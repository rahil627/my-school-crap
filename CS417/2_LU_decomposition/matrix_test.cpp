//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575
//compiled with GNU GCC

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <cmath>
#include <ctime>
#include <iomanip>
using namespace std;
#include "matrix.h"

//main--------------------------------------------------------------------------------------------
int main()
{
    srand(time(NULL));
    matrix M(3);
    M.display();
    if(M.check()==true){cout<<"found a 0 on the diagonal"<<endl;return 0;}
    M.solve();
    M.display2();
    return 0;
}
