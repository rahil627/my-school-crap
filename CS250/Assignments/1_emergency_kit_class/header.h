class flashlight
{
private:
	string flashlightType;
	int required;
public:
	flashlight(){flashlightType="n/a", required=0;}//constructor + defaults
 //~flashlight();//destructor
	   void setDefault(){flashlightType="n/a"; required=0;}
	   void set(string f, int r){flashlightType=f; required=r;}
       void destroy();
	   void display();
};
class battery
{
private:
	string batteryType1, expiration1, batteryType2, expiration2;
	int amount1, amount2;
public:
	battery(){batteryType1="n/a", amount1=0, expiration1="00/00/0000", batteryType2="n/a", amount2=0, expiration2="00/00/0000";}//constructor + defaults
  //~battery(){cout<<"Batteries have been destroyed"<<endl;}//destructor
	   void setDefault1(){batteryType1="n/a"; amount1=0; expiration1="00/00/0000";}
	   void setDefault2(){batteryType2="n/a"; amount2=0; expiration2="00/00/0000";}
	   void set1(string a, int b, string c){batteryType1=a; amount1=b; expiration1=c;}
	   void set2(string a, int b, string c){batteryType2=a; amount2=b; expiration2=c;}
	   void display();
};
class kit
{
public:
	kit(){lightstick=0, water=false, aspirin=false, bandaid=false;} //constructor + defaults
 //~kit(){cout<<"Kit has been destroyed"<<endl;}//destructor
	   void set(bool w, int l, bool a, bool b){water=w; lightstick=l; aspirin=a; bandaid=b;}
	   void setWater(bool w){water=w;}
	   void setLightstick(int l){lightstick=l;}
	   void setAspirin(bool a){aspirin=a;}
	   void setBandaid(bool b){bandaid=b;}
	   void display();

private:
        bool water, aspirin, bandaid;
		int lightstick;
};
void flashlight::display()
{
	if(required==0);
	else
	{
		cout<<"Flashlight - battery type: "<<flashlightType<<endl
			<<"           - number of batteries required: "<<required<<endl;
	}
}
void battery::display()
{
	if(amount1==0);
	else
	{
		cout<<"Battery Set 1 - "<<amount1<<" "<<batteryType1<<" batteries"<<endl
			<<"              - expiration date:"<<expiration1<<endl
			<<endl;
	}
	if(amount2==0);
	else
	{
		cout<<"Battery Set 2 - "<<amount2<<" "<<batteryType2<<" batteries"<<endl
			<<"              - expiration date:"<<expiration2<<endl
			<<endl;
	}
}
void kit::display()
{
	if(water==true){cout<<"Collapsible Water Container"<<endl;}else;
	if(lightstick==0);else{cout<<lightstick<<" Lighsticks"<<endl;}
	if(aspirin==true){cout<<"Aspirin"<<endl;}else;
	if(bandaid==true){cout<<"Tin of Bandaids"<<endl;}else;
}