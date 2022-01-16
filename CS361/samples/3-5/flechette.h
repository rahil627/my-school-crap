class flechette
{
public:
    flechette(int i, double x, double y);
    void display(){cout<<"F"<<id<<" at ("<<
                   loc[0]<<", "<<loc[1]<<")"<<endl;}
    double getx(){return loc[0];}
    double gety(){return loc[1];}

    void getsig(sensor s);

    void addsensor(sensor * sptr);
    void report();

private:
    double loc[2];
    int id;
    list <sensor> slist;
    list <sensor>::iterator sitr;

};

flechette::flechette(int i, double x, double y)
{
    id=i;
    double dx, dy;
    dx=normal(0,1);
    loc[0]=10*x+dx;
    dy=normal(0,1);
    cout<<dx<<" "<<dy<<" for F"<<id<<endl;
    loc[1]=10*y+dy;
}

void flechette::getsig(sensor s)
{
    double val = s.sendsig();
    char T = s.sendtype();
    if(val!=1000)cout<<T<<" signal received: "<<val<<endl;
    else{cout<<"no signal"<<endl;}
}

void flechette::addsensor(sensor * sptr)
    {
    if (sptr==NULL){return;}
    slist.push_back(*sptr);
    }

void flechette::report()
    {double val;
     char T;
    sitr=slist.begin();
    while (sitr!=slist.end() )
        { val = sitr->sendsig();
          T = sitr->sendtype();
          if(val!=1000)
            {cout<<T<<" signal received: "<<val<<endl;}
          else{cout<<"no signal"<<endl;}
        sitr++;
        }
    }
