class goose
{
public:
       //constructor
       goose();
       //accessors
       double getwspan(){return wspan;}
       double getwt(){return wt;}
       bool getbanded(){return banded;}
       string getband(){return bandnum;}
       char getgender(){return gender;}
       //mutators
       void setwspan(double w){wspan=w;}
       void setwt(double w){wt = w;}
       void setbanded(bool b){banded = b;}
       void setband(string b){bandnum=b;}
       void setgender(char g){gender = g;}
       //destructor
       ~goose(){cout<<"BANG...goose is dead"<<endl;}
       //other interesting functions
       void display();
private:
        double wspan;
        double wt;
        bool banded;
        string bandnum;
        char gender;

};

goose::goose()
              {wspan=0;
              wt=0;
              banded=false;
              gender='n';
              }

void goose::display()
     {
     cout<<"wing span:"<<wspan<<endl;
     cout<<"weight   :"<<wt<<endl;
     if(banded){cout<<"band number: "<<bandnum<<endl;}
     cout<<"gender: "<<gender<<endl;
     }
