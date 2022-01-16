//************************************************************************************************
//Rahil Patel & Katrina
//00579757		00
//Lab Session: 3:00-4:15
//Instructor:  Quingwen Xu
//To implement a personnel accounting application involving employees of a local firm
//uses an array extensively
//************************************************************************************************

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <fstream>
#include <iomanip>
#include <string>
using namespace std;

//structs-----------------------------------------------------------------------------------------
struct info
{
	string name;
	int id;
	char paytype;
	float salary;
	string department, title;
};

//function prototypes-----------------------------------------------------------------------------
void displaymenu();
void getData(ifstream& ifile, info employees[]);
void case1(info employees[], int searchid);
void case2(info employees[], int salary);
void case3(info employees[], int departmentchoice);
void case4(info employees[]);
void case5();
void case6();
void case7();
void case8();

//main--------------------------------------------------------------------------------------------
int main()
{//main
	
	//declarations
	ifstream ifile;

	ifile.open("C:\Documents and Settings\Rahil Patel\My Documents\School\CS 150\5empdata.txt");

	if (!ifile)
	{
		cout<<"Cannot open the input file."<<endl;
		return 1;
	}

	getData(ifile, employees);



while (menuchoice<9)
{//while
	
	displaymenu();

	cin>>menuchoice;
	cout<<endl;
	


	switch(menuchoice)
	{//switch
//================================================================================================
		case 1: //To search for an employee, given his/her ID # (input from keyboard) and display name, title and department. If no match is found proper error message should be displayed.
				cout<<"Enter the employee's ID number\n";
				cin>>searchid;
				case1(employees, searchid);
				break;
//================================================================================================
		case 2: //To display all employees’ names whose salary is above the particular number (input from keyboard).	
				cout<<"Enter a number to see the employees whose salary is above the entered number\n";
				cin>>salary;
				case2(employees, salary);
				break;
//================================================================================================		
		case 3: //To group employees by department (input from keyboard) and display the list of names.
				cout<<"Which department do you want to see?\n"
					<<"1. Marketing\n"
					<<"2. Administration\n"
					<<"3. HR\n"
					<<"4. Production\n";
				cin>>departmentchoice;
				case3(employees, departmentchoice); //ERROR!!!...
				break;
//================================================================================================
		case 4: //To sort data by ascending order of employee ID numbers and display employee names along with their ID numbers.
				case4(employees);
				break;
//================================================================================================
		case 5: //To provide the payroll department with a list of employees sorted by ID #, grouped by department. For each department, display employee names and their bi-weekly pay against their ID #.
				break;
		case 6:	//To display employees, by name, of a particular title (input from keyboard).
		break;//6
//================================================================================================
		case 7:	//Addition of a new employee: Samuel Johnson, 6, s, 150000, Production, Technical Analyst.

				for(c=15;c<16;c++)//only works for 1 employee update, need to make NoOfEmployees in GetData or double for loop?
				{
					cout<<"Enter the name of the new employee.\n";
					cin	>>employees[c].name;
					cout<<"Enter the ID of the new employee.\n";
					cin	>>employees[c].id;
					cout<<"Enter the paytypee of the new employee.\n";
					cin	>>employees[c].paytype;
					cout<<"Enter the salary of the new employee.\n";
					cin	>>employees[c].salary;
					cout<<"Enter the department of the new employee.\n";
					cin	>>employees[c].department;
					cout<<"Enter the title of the new employee.\n";
					cin	>>employees[c].title;
				}	
				break;
//================================================================================================
		case 8:	// prints out to an output file “empoutput.txt” the list of names of employees sorted by ID # (including those who have been added new).
				break;
//================================================================================================
	}//switch
}//while

	ifile.close();
	return 0;
}//main

//functions---------------------------------------------------------------------------------------
void array()
{}
void displaymenu()
{
	cout<<"\n********************************************************************************"
		<<"Welcome to the Management Solutions accounting system\n"
		<<"1. Search Employee\n"
		<<"2. Display employees sorted by their salaries\n"
		<<"3. Display employees sorted by their department\n"
		<<"4. Display employees sorted by their ID numbers\n"
		<<"5. Display employees sorted by their paytype and department\n"
		<<"6. Display employees sorted by their title\n"
		<<"7. Add employees\n"
		<<"8. Quit\n"
		<<"********************************************************************************";
}

void getData(ifstream& ifile, info employees[])
{	
	for(int c=0;!ifile.eof();c++)
	{
		getline(ifile,employees[c].name);
		ifile >>employees[c].id
			  >>employees[c].paytype
			  >>employees[c].salary;
		ifile.ignore('\n');
	    getline(ifile,employees[c].department);
		getline(ifile,employees[c].title);;
	} c--;//not sure why...
}

void case1(info employees[], int searchid)
{				
	cout<<endl;
	//need error if ID number doesn't match
	for(int c=0;c<65;c++)
		{//for
			if (employees[c].id==searchid)
			{cout	<<employees[c].name<<endl
					<<employees[c].department<<endl
					<<employees[c].title<<endl;}
		}//for
}

void case2(info employees[], int salary)
{	
	cout<<endl;
	for(int c=0;c<65;c++)
	{//for
		if (employees[c].salary>salary)
		{cout<<employees[c].name<<endl;}
	}//for
}

void case3(info employees[], int departmentchoice)
{//case3
	cout<<endl;
	int c;
	string marketing, administration, hr, production;

	switch(departmentchoice) //marketing string not == to file?
	{//switch2
		case 1: for(c=0;c<65;c++)
				{//for
					if (employees[c].department==marketing)
					{cout<<employees[c].name;}
				}//for
				break;
		case 2: for(c=0;c<65;c++)
				{//for
					if (employees[c].department==administration)
					{cout<<employees[c].name;}
				 }//for
				break;
		case 3: for(c=0;c<65;c++)
				{//for
					if (employees[c].department==hr)
					{cout<<employees[c].name;}
				 }//for
				break;
		case 4: for(c=0;c<65;c++)
				{//for
					if (employees[c].department==production)
					{cout<<employees[c].name;}
				}//for
				break;
//		case 5: //quit
	}//switch2	
}//case3

void case4(info employees[])
{
	int index, smallestIndex, temp, minIndex, c;

	for (index=0;index<65-1;index++)
	{//for1
		smallestIndex=index;
		for(minIndex=index+1;minIndex<65;minIndex++)
			if(employees[minIndex]<employees[smallestIndex])
				smallestIndex=minIndex;
		temp=employees[smallestIndex];
		employees[smallestIndex]=employees[index];
		employees[index]=temp;
	}//for1
				
	for(c=0;c<65;c++)
	{
		cout<<employees[c].ID
			<<setw(25)<<employees[c].name;
	}
}



/*test
For Part C:

To create the required reports/output, 
run your program and give inputs from keyboard to simulate the above mentioned activities. 

  The following are some values/events that you need to use as your keyboard inputs.

1:  Search for ID numbers (1) 15 (2) 12.
2:  All employees with salary above $160000.
3:  Group by department of Marketing.
6:  Display the list of employees who are Trainees.
*/

/*Stuff to Fix
Switch inside of switch
*/