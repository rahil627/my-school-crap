//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <string>
//#include <cstdlib>
#include <ctime>
using namespace std;
#include "board.h"
#include "property.h"

//function prototypes-----------------------------------------------------------------------------


//main--------------------------------------------------------------------------------------------
int main()
{
	/*
	board *b;
	b=new board();
	b->display();
	*/
	//make it constant?
	board b;
	b.construct();
	b.display();
	

	return 0;
}

//functions---------------------------------------------------------------------------------------
