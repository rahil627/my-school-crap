//************************************************************************************************
//Rahil Patel + Tony Bergio + Katrina 
//00579757		00
//very basic C++
//************************************************************************************************

#include <iostream>
#include <fstream>
#include <iomanip>
#include <string>

using namespace std;

int withdrawalf(double balance,double withdrawal)
{balance=balance-withdrawal;
return(balance);}


int depositf(double balance, double deposit)
{balance=balance+deposit;
return(balance);}

int balancef(double balance)
{return(balance);}

//int exitf()




/*
getAcctNum()
getPIN()
dispMenu()
noMatch()
update()
--by Tony the Tiger
*/


int main()
{
	ifstream dat;
	int id1,id2,pin1,pin2,choice;
	string name;
	double b,w,d;

	cout<<"*******************************************************************************"<<endl
		<<"WELCOME TO BANK OF THE UNIVERSE"<<endl
		<<"********************************************************************************"
		<<"Enter Your Account ID #: ";
	cin>>id1;

	dat.open("a:\\customer.dat");
	dat>>id2>>pin2>>name>>name>>b; //add space between names somehow....2 customer sets? awww shit
//--------------------------------------------------------------------------
	if (id1=id2)
	{cout<<"Enter Your Pin #: ";
	cin>>pin1;}
	
	else 
	{cout<<"Account # does not match database"<<endl;
	return 1;} //this shit isn't working
//--------------------------------------------------------------------------
	if (pin1!=pin2)
	{cout<<"Pin # does not match with Account #";
	return 1;} //neither is this one god damn it
//--------------------------------------------------------------------------


		while (choice<5)
		{//loop
		cout<<"*******************************************************************************"<<endl
			<<"WELCOME TO BANK OF THE UNIVERSE"<<endl
			<<"********************************************************************************"
			<<"1. Withdrawal"<<endl          
			<<"2. Deposit"<<endl
			<<"3. Update"<<endl
			<<"4. Balance"<<endl
			<<"5. Quit"<<endl
			<<"Enter a Choice #: ";
		cin>>choice;

	switch(choice)
	{case 1: cout<<"How much do you want to withdraw?: ";
			cin>>w;
			cout<<endl;
			if (w>b)
				cout<<"That transaction cannot be carried out";
			else
			withdrawalf(b,w);
			break;
	case 2: cout<<"How much do you want to deposit?: ";
			cin>>d;
			depositf(b,d);
			break;
	case 3: //too lazy
			break;
	case 4: cout<<"Your balance is "<<balancef(b)<<"."<<endl;
			break;
	case 5: return 1;//yay, more retarded returns
			break;
	default: cout<<"Sorry, that was not a choice"<<endl;}
		}//loop

	dat.close();

	return 0;
}