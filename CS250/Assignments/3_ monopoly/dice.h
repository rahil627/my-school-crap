class dice
{
 private:
	int r1, r2, r3;
 public:
	//dice();
	void roll()
	{
		r1=(rand()%6)+1;
		r2=(rand()%6)+1;
		r3=r1+r2;
	}
	int getR1(){return r1;}
	int getR2(){return r2;}
	void display(){cout<<"you rolled a "<<r1<<" + "<<r2<<" = "<<r3<<endl;}
	void doubles(){cout<<"you rolled doubles! it's your turn again"<<endl;}
};
//functions---------------------------------------------------------------------------------------
