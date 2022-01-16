//************************************************************************************************
//Rahil Patel
//00579757
//Lab Session: 3:00-4:15
//Instructor:  Quingwen Xu
//To survey zoos all around the world and inventory all animals of a particular species and store them into a link list. 
//This program uses a link list concept without using the cstdlib
//************************************************************************************************

//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <fstream>
#include <iomanip>
#include <string>
using namespace std;

struct node
{
	string zoo;
	char gender[5];
	int age, id;
    node *nxt;// Pointer to next node
	node *last;
};

//function prototypes-----------------------------------------------------------------------------
void load_list(ifstream& ifile, node *start_ptr, node *last);
void displaymenu();
void display_list(node *start_ptr);
void add_panda(node *start_ptr);
void delete_panda(node *start_ptr);
void search_by_gender();
void sort_by_zoo();
void exit(ofstream& ofile, node *start_ptr);

//main--------------------------------------------------------------------------------------------
int main()
{//main
	//declarations
	int menuchoice;
	node *start_ptr = NULL;
	node *last = NULL;
	
	ifstream ifile;
	ofstream ofile;

	ifile.open("a:\\zoo.dat");
	ofile.open("a:\\zoo.dat");
	if (!ifile)
	{
		cout<<"Cannot open the input file."<<endl;
		return 1;
	}
	
	while (menuchoice!=0)
	{//while
	
		displaymenu();
		cin>>menuchoice;
		cout<<endl;

		switch(menuchoice)
		{//switch
			case 1:	load_list(ifile, start_ptr, last);	break;
			case 2:	display_list(start_ptr);			break;	
			case 3:	add_panda(start_ptr);				break;
			case 4:	delete_panda(start_ptr);			break;
			case 5:	search_by_gender();					break;
			case 6:	sort_by_zoo();						break;
			case 7:	exit(ofile, start_ptr);	
					ifile.close();
					return 0;							break;
		}//switch
	}//while

	ifile.close();
	return 0;
}//main

//functions---------------------------------------------------------------------------------------
void displaymenu()
{
	cout<<"\n********************************************************************************"
		<<"Welcome to the Zoo Survey System\n"
		<<"1. Load list\n"
		<<"2. Display\n"
		<<"3. Add a panda\n"
		<<"4. Delete a panda\n"
		<<"5. Search by gender\n"
		<<"6. Sort by name of zoo\n"
		<<"7. Exit\n"
		<<"********************************************************************************";
}
void load_list(ifstream& ifile, node *start_ptr, node *last)
{//load
	node *temp;
	while (temp != NULL)
	{//while
			temp=new node;
			getline	(ifile, temp->zoo);
			ifile	>> temp->gender
					>> temp->age
					>> temp->id;
			ifile.ignore('\n');
			temp = temp->nxt;

			if (start_ptr == NULL)
			{
				start_ptr = temp;
				last= temp;
			}
			else
			{
				last->nxt = temp;
				last = temp;
			}

	}//while
}//load
void display_list(node *start_ptr)
{//display_list
	 node *temp;
     temp = start_ptr;
     cout << endl;
     if (temp == NULL)
       cout << "The list is empty!" << endl;
     else
       { while (temp != NULL)
	   {  // Display details for what temp points to
			cout	<< setw(10)		<< temp->zoo << " "
					<< temp->gender	<< " "
					<< temp->age		<< " "
					<< temp->id
				    << endl;
	      temp = temp->nxt;

	   }
	 cout << "End of list!" << endl;
       }
}//display_list
void add_panda(node *start_ptr)
{//add
	node *temp, *temp2;   // Temporary pointers
    // Reserve space for new node and fill it with data
    temp = new node;
    cout << "Please enter the name of the zoo: ";
    cin >> temp->zoo;
    cout << "Please enter the gender of the panda: ";
    cin >> temp->gender;
    cout << "Please enter the age of the panda: ";
    cin >> temp->age;
	cout << "Please enter the id of the panda: ";
	cin >> temp->id;
    temp->nxt = NULL;

    // Set up link to this node
    if (start_ptr == NULL)
       { start_ptr = temp;}
    else
       { temp2 = start_ptr;
         // We know this is not NULL - list not empty!
         while (temp2->nxt != NULL)
           {  temp2 = temp2->nxt;
              // Move to next link in chain
           }
         temp2->nxt = temp;
       }
}//add
void delete_panda(node *start_ptr)
{//delete_panda
	node *temp1, *temp2;
     if (start_ptr == NULL)
          cout << "The list is empty!" << endl;
     else
        { 
		 temp1 = start_ptr;
          if (temp1->nxt == NULL)
             { 
			  delete temp1;
              start_ptr = NULL;
             }
          else
             { 
				while (temp1->nxt != NULL)
				{ 
					temp2 = temp1;
					temp1 = temp1->nxt;
				}
               delete temp1;
               temp2->nxt = NULL;
             }
        }
}//delete_panda
void search_by_gender()
{//search_by_gender
}//search_by_gender
void sort_by_zoo()
{//sort_by_zoo
}//sort_by_zoo
void exit(ofstream& ofile, node *start_ptr)
{//exit
	node *temp;
    temp = start_ptr;
	while (temp != NULL)
	{//while
		ofile	<< temp->zoo	<<endl
				<< temp->gender	<<endl
				<< temp->age	<<endl
				<< temp->id		<<endl;
		temp = temp->nxt;
	}//while

}//exit