class element
{
public:
       element(){}
       element(string n, int a){name = n; atomicnum=a;}
       void display(){cout<<name<<" #"<<atomicnum<<endl;}
       string getname(){return name;}
       int getan(){return atomicnum;}
protected:
        string name;
        int atomicnum;
};

class isotope:public element
{
public:
       isotope(string n, int a, int neu){name=n; atomicnum=a; neutrons = neu;}
       isotope(element * eptr, string n, int neu);//not used in version 2
       void set(string n, int neu){name = n; neutrons=neu;}
       void display(){element::display(); cout<<neutrons<<" neutrons"<<endl;}
private:
        int neutrons;
};


isotope::isotope(element * eptr, string n, int neu)
                         {
                         name = n;
                         atomicnum=eptr->getan();
                         neutrons = neu;
                         }
