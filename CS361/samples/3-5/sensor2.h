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
char type; //V, A, R (vibration, acoustic, radio)
int id; 
double Loc[2];
double signal;
double mean;
double stdiv;
double temp;
};
/*
for acoustic sensors 
    sound spikes of at least 30 db will be reported

for vibration sensor
    vibration spikes of at least 20 Hz

for radio sensor
    signals of at least .1 Mhz.
*/



sensor::sensor(int i, char C)
{
id = i;
Loc[0]=(double(rand()%900-99))/10.0;
Loc[1]=normal(35,10);
    switch(C)
        {
        case 'V':{mean = 16, stdiv=3.5;}break;
                  
        }
}

double sensor::sendsig()
    {signal = normal(mean,stdiv);
//cout<<"my signal is = "<<signal<<endl;
     if (signal>20 ) 
        {return signal;}
     else{return 1000;}
}

void sensor::display()
{
    cout<<"sensor "<<id<<" has signal = "
        <<signal
        <<" at ("<<Loc[0]<<", "<<Loc[1]<<")"<<endl;

}