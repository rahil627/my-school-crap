class player
{
private:
	string name, token;
	int cash, 
		position;//dividing into 500's, 100's, 20's, 10's, 5's, 1's is too much

public:
	//using link-list
	//player(){name="default"; token="default"; cash=1500; position=0;}
	//player(string n, string t){name=n; token=t; cash=1500; position=0;}

	//using normal class
	void set(string n, string t){name=n; token=t; cash=1500; position=0;}
};