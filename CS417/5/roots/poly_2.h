class polyterm
{
public:
    polyterm(double c, double e){coeff=c; expo=e;}
    double getc(){return coeff;}
    double gete(){return expo;}
    void display(){cout<<coeff<<"x^"<<expo;}
private:
    double coeff;
    double expo;

};
//**********************************polynomial class
class poly
{
public:
    poly(){}
    void add(double c, double e);
    void showp();
    void getvalues(double c[], double e[]);
    double eval(double x);
private:
    list<polyterm> equation;
};

void poly::add(double c, double e)
{
    polyterm * pptr = new polyterm(c,e);
    equation.push_back(*pptr);
    
}

void poly::showp()
{
   list<polyterm>::iterator pitr=equation.begin();
   while(pitr!=equation.end() )
      {pitr->display(); cout<<" + "; pitr++;}
    cout<<endl;
}


void poly::getvalues(double c[], double e[])
{int i=0;
   list<polyterm>::iterator pitr=equation.begin();
   while(pitr!=equation.end() )
      {c[i]=pitr->getc(); 
       e[i]=pitr->gete();
       i++;
       pitr++;}

}


double poly::eval(double x)
{
double v=0;

 list<polyterm>::iterator pitr=equation.begin();
   while(pitr!=equation.end() )
      {
        v+=pitr->getc()*pow(x,pitr->gete());
   
       pitr++;}


return v;
}






