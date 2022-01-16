class sensor
{
private:
    char type; //T, A, R (thermal, acoustic, radio)
    int id;
    double Loc[2];
    double signal;
    double mean;
    double stdiv;
    double temp;
public:
    sensor(int i);
    void normsig();//normalize signal or "generate reasonable data"
    void setnoise(double s){signal=s;}
    void settemp(double t){temp = t;}
    double getx(){return Loc[0];}
    double gety(){return Loc[1];}
    double getsig(){return signal;}
    char gettype(){return type;}
    void settype(char t){type=t;}
    void display();
};
sensor::sensor(int i)
{
id = i;
Loc[0]=double(rand()%10000)/100.0;//random x to 2 decimal places
//Loc[1]=normal(50,10);//random y
Loc[1]=normal(50,15);//normal y
mean=24.6;
stdiv=2;
temp=24.6;
signal=1000;
}
void sensor::normsig()//background noise spikes
{
    double normsig;
    normsig = normal(mean,stdiv);
    if( (normsig>temp+3.5*stdiv)||(normsig<temp-3.5*stdiv) ){signal=normsig;}
    else{signal=1000;}
}
void sensor::display()
{
    if(signal<1000)
    {
        cout<<"sensor "<<setw(3)<<id<<" received ";
        switch(type)
        {
            case 'T':{cout<<"Thermal ";}			    break;
            case 'A':{cout<<"Acoustic ";}	            break;
            case 'R':{cout<<"Radio ";}                 break;
        }
        cout<<setw(5)<<setprecision(4)<<signal<<" at ("
            <<setw(5)<<Loc[0]<<", "<<setw(5)<<setprecision(4)<<Loc[1]<<")"<<endl;
    }
}
