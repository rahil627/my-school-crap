class flechette
{
private:
    double loc[2];
    int id;
    list <sensor> slist;
    list <sensor>::iterator sitr;
public:
    flechette(int i, double x, double y);
    void display(){cout<<"F"<<id<<" at ("<<
                   loc[0]<<", "<<loc[1]<<")"<<endl;}
    double getx(){return loc[0];}
    double gety(){return loc[1];}
    int getid(){return id;}

    void getsig(sensor s);

    void addsensor(sensor * sptr);
    void report(target t);
};

flechette::flechette(int i, double x, double y)
{
    id=i;
    double dx, dy;
    dx=normal(0,1);
    loc[0]=10*x+dx;
    dy=normal(0,1);
    loc[1]=10*y+dy;
}

void flechette::getsig(sensor s)
{
    double val = s.getsig();
    char T = s.gettype();
    if(val!=1000)cout<<T<<" signal received: "<<val<<endl;
    else{cout<<"no signal"<<endl;}
}

void flechette::addsensor(sensor * sptr)
{
    //if (sptr==NULL){return;}
    slist.push_back(*sptr);
}

void flechette::report(target t)
{
    double val;
    char T;
    double temp=0;
    double largestsig=1000;
    sitr=slist.begin();
    while (sitr!=slist.end())
    {
        sitr->normsig();
        t.makenoise(sitr);
        sitr->display();
        val = sitr->getsig();
        T = sitr->gettype();
        //figures out largest signal
        if(val>temp&&val!=1000)
        {temp=val;}
        if(temp==0)
        {largestsig==1000;}
        else{largestsig=temp;}
        sitr++;
    }
    //displays largest signal
    if(largestsig!=1000)
    {
        cout<<"\nflechette "<<id<<" "<<"received ";
        switch(T)
        {
            case 'T':{cout<<"Thermal";}			    break;
            case 'A':{cout<<"Acoustic";}	            break;
            case 'R':{cout<<"Radio";}                 break;
        }
        cout<<setw(5)<<setprecision(4)<<" signal: "<<largestsig
            <<" at ("<<setw(5)<<loc[0]<<", "<<setw(5)<<setprecision(4)<<loc[1]<<")"<<endl<<endl;
    }
}
