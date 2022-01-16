class property//special
{
 protected:
	string name;
	char type;
	int position;
	property*next;
	property(){name="default";}
 public:
	property(string n, int p0){name=n; position=p0; type='S';}
	char getType(){return type;}
	property *getNext(){return next;}//gets the next node
	void setNext(property*n){next=n;}//used to add a skill in front of list
	//void makeNew(string n){next = new property(name);}//puts new info into the next node
	void display(){cout<<"____________________________\n"<<"name: "<<name<<endl<<"position: "<<position<<endl;}
};
class ownable:public property//not a square-for inheritance purposes only
{
 protected:
	int price, rent, mortgageValue;
	bool owned, mortgage;//if(mortgage==true){show mortgageValue+(mortgageValue*(1/10);}else{show mortgageValue;}
	ownable(){price=0; rent=0; mortgageValue=0; owned=false; mortgage=false;}

 public:
	 ownable(int p, int r0, int v){price=p; rent=r0; mortgageValue=v; /*owned=false; mortgage=false;*/}
	 void display(){property::display();cout<<"price: "<<price<<"\nrent: "<<rent<<"\nmortgage value: "<<mortgageValue<<endl;}
};
class residential:public ownable
{
 protected:
	 int rentHouse1, rentHouse2, rentHouse3, rentHouse4, rentHotel, costHouse;
	 string color;
	 residential(){color="default"; costHouse=0; rentHouse1=0; rentHouse2=0; rentHouse3=0; rentHouse4=0; rentHotel=0;}

 public:
	 residential(string n, int p0, string c, int p, int c1, int r0, int r1, int r2, int r3, int r4, int r5, int v)
	 {name=n; position=p0; color=c; price=p; rent=r0; costHouse=c1; rentHouse1=r1; rentHouse2=r2; rentHouse3=r3; rentHouse4=r4;
	 rentHotel=r5; mortgageValue=v; type='D';}
	 void display(){ownable::display();cout<<"color: "<<color<<"\nrentHouse1: "<<rentHouse1<<"\nrentHouse2: "<<rentHouse2<<"\nrentHouse3: "<<rentHouse3<<"\nrentHouse4: "<<rentHouse4<<"\nrentHotel: "<<rentHotel<<endl;}
};
class railroad:public ownable
{
 private:
	//int noOfOwned;
 public:
	 railroad(string n, int p0){name=n; position=p0; price=200; rent=25; mortgageValue=100;/*for now...*/type='R';}
	//if(1 railroad is owned){rent=25;}
	//if(2){rent=50;}
	//if(3){rent=100;}
	//if(4){rent=200;}
	 void display(){ownable::display();}
};
class utility:public ownable
{
 private:
	 //int noOfOwned;

 public:
	 utility(string n, int p0){name=n; position=p0; price=150; rent=25; type='U';}//include under dice & player!
	//if(1 utility is owned){rent=4*roll;}
	//if(2){rent=10*roll;}
	 void display(){ownable::display();}
};