class sensor
{
public:
    sensor(int i, char C);
    double sendsig();
    void settemp(double t){temp = t;}
    double getx(){return Loc[0];}
    double gety(){return Loc[1];}
    char sendtype(){return type;}
    void display();
private:
char type; //T, A, R (thermal, acoustic, radio)
int id;
double Loc[2];
double signal;
double mean;
double stdiv;
double temp;
};

sensor::sensor(int i, char C)
{
id = i;
Loc[0]=(double(rand()%900-99))/10.0;
Loc[1]=normal(35,10);
    switch(C)
        {
        case 'T':{mean = 24.6, stdiv=2;}break;

        }
}

double sensor::sendsig()
    {signal = normal(mean,stdiv);
     if( (signal>temp+3.5*stdiv)||(signal<temp-3.5*stdiv) )
        {return signal;}
     else{return 1000;}
}

void sensor::display()
{
    cout<<"sensor "<<id<<" has signal = "
        <<signal
        <<" at ("<<Loc[0]<<", "<<Loc[1]<<")"<<endl;

}