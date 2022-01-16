class card
{
 private:
    string name;
    int random, id;//ID to show random works
    bool indeck;
 //protected:
	 //int card[16];
 public:
	card(){indeck = true; random=rand();}
	void setName(string n){name=n;}
	void setID(int d){id=d;}
	void setRandom(int r){random=r;}
	int getRandom(){return random;}
	bool getindeck(){return indeck;}
	void remove(){indeck = false;}

	void display(){cout<<"name: "<<name<<endl
					   <<"random value: "<<random<<endl
					   <<"id: "<<id<<endl<<endl;}
};