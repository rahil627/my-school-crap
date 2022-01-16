struct attachments
{
 bool Dozer;
 bool Broom; 
 bool Blower;
 bool Rake;
 bool Blade;
 bool Digger;
 bool Backhoe;
 bool Loader;

};

class tractor
{
public:
       tractor();
       void setmake(string m){make = m;}
       void setmodel(int m){model = m;}
       void sethp(int h){horsepower = h;}
       void setpto(int p){PTO=p;}
       void setbrakes(bool b){brakes = b;}
       void setloader(bool l){A.Loader = l;}
       void sethoe(bool h){A.Backhoe=h;}
       void setdigger(bool d){A.Digger=d;}
       void setblade(bool b){A.Blade = b;}
       void setrake(bool r){A.Rake=r;}
       void setblow(bool b){A.Blower=b;}
       void setbroom(bool b){A.Broom=b;}
       void setdozer(bool d){A.Dozer=d;}
       void display();

       

private:
string make;
int model;
int horsepower;
int PTO;
bool brakes;
attachments A;
};

tractor::tractor()
                  {
                 A.Loader = false;
                 A.Backhoe=false;
                 A.Digger=false;
                 A.Blade = false;
                 A.Rake=false;
                 A.Blower=false;
                 A.Broom=false;
                 A.Dozer=false;
                  }

void tractor::display()
     {
     cout<<make<<" model "<<model<<", "<<horsepower<<" horsepower with ";
     cout<<PTO<<" horse power take off "<<endl;
     if(brakes){cout<<" with oil cooled disc brakes also includes:"<<endl;}
     if(A.Loader){cout<<" front end loader" ;}
     if(A.Backhoe){cout<<", backhoe" ;}
     if(A.Digger){cout<<", post hole digger" ;}
     if(A.Blade){cout<<", blade" ;}
     if(A.Rake){cout<<", Hay rake" ;}
     if(A.Blower){cout<<", snow blower" ;}
     if(A.Broom){cout<<", rotary broom" ;}
     if(A.Dozer){cout<<", dozer attachments" ;}
cout<<endl<<endl;
}
