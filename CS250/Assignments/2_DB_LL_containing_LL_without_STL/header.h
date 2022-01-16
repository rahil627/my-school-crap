//information-------------------------------------------------------------------------------------
//Rahil Patel	00579575	Partner-Benjamin
//Mr. Ranjan 11:00AM-12:15PM
//To implement a linkable class (personnel) as a part of an employee management system
//for the National Security Agency (NSA).

//classes-----------------------------------------------------------------------------------------
class skillSet
{
 private://skillSet variables
	string skill;
	skillSet *next;
 public://skillSet functions
	//constructors
	skillSet();//default
	skillSet(string s){skill=s; next=NULL;}

	//destructor
	~skillSet(){cout<<skill<<" skill destroyed"<<endl;}

	//get
	string getSkill(){return skill;}

	//link-list
	void setNext(skillSet *n){next=n;}//used to add a skill in front of list
	skillSet *getNext(){return next;}//gets the next node
	void makeNew(string s){next = new skillSet(s);}//puts new info into the next node

	//other
	void display(){cout<<endl<<skill;}
};
class personnel
{
 private://personnel variables
	string lastName, firstName;
	int id, securityLevel;
	personnel *next;//points to the next node
	skillSet *pSkillSet;//skillSet link-list

 public://personnel functions
	//constructors;
	personnel();//default
	personnel(string L, string F, int I, int S, skillSet *s)
	{lastName=L; firstName=F; id=I; securityLevel=S; pSkillSet=s; next=NULL;}

	//destructor
    ~personnel(){cout<<firstName<<" "<<lastName<<" has been successfully deleted"<<endl;}

	//set
	void setId(int I){id=I;}
	void setPSkillSet(skillSet *s){pSkillSet=s;}

    //get
	string getLastName(){return lastName;}
	string getFirstName(){return firstName;}
	int getId(){return id;}
	int getSecurityLevel(){return securityLevel;}
	skillSet * getPSkillSet(){return pSkillSet;}

    //link-list  
    void setNext(personnel *n){next=n;}//used to add a skill in front of list
    personnel *getNext(){return next;}//gets the next node
	void makeNew(string L, string F, int I, int S, skillSet *s){next = new personnel(L, F, I, S, s);}//puts new info into the next node

	//other
	void display();
};

//long functions----------------------------------------------------------------------------------
void personnel::display()//maybe set width? add the first line!
{
	skillSet *c1;//needed so I do not change the value of pSkillSet
	c1=pSkillSet;
	cout<<"-----General-Info-----\n"
		<<"Name: "<<lastName<<", "<<firstName<<endl
		<<"ID: "<<id<<endl
		<<"Security Level: ";
	if(securityLevel==1){cout<<"Normal";}
	if(securityLevel==2){cout<<"High";}
	if(securityLevel==3){cout<<"Secret";}
	if(securityLevel==4){cout<<"Top Secret";}
	if(pSkillSet==NULL){/*don't display anything*/;}
	else
	{
		cout<<"\n--------Skills--------";
		while(c1!=NULL)
		{
			c1->display();
			c1=c1->getNext();
		}
	}
	cout<<"\n____________________________\n";
}