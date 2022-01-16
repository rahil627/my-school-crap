//pre-processors----------------------------------------------------------------------------------
#include <iostream>
//#include <fstream>
//#include <string>
#include <ctime>
using namespace std;
#include "dice.h"

//function prototypes-----------------------------------------------------------------------------

//main--------------------------------------------------------------------------------------------
int main()
{
	dice d;

	//srand((unsigned)time(0));
	srand(time(NULL));
	
	d.roll();//can put everything into the roll function of dice.h later on
	d.display();
	if(d.getR1()==d.getR2()){d.doubles();}
	return 0;
}

//functions---------------------------------------------------------------------------------------
