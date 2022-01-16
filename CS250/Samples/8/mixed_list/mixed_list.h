class beta
{
 public:
    beta(){next = NULL; type='b';}
    void setnext(beta * n){next =n;}
    beta * getnext(){return next;}
    char gettype(){return type;}
    void display()
             {if(type!='b')
                           {cout<<"it appears I am a beta object"<<endl;
                           cout<<"but in reality I an instance of derived class "<<type<<endl<<endl;
                           }
              else{cout<<"I am a genuine beta object"<<endl<<endl;}
              }

   ~beta(){cout<<"ARRRRGH...a dying beta"<<endl<<endl;}

 protected:
     char type;
     beta * next;
};

class D: public beta
{
public:
       D(int i){type='d'; id=i;}
       void display()
            {
            cout<<"my id is "<<id<<endl;
            beta::display();
            }
                        
private:
       int id;
       
};


class E: public beta
{
public:
       E(int i){type='e'; id=i;}
       void display()
            {
            cout<<"my id is "<<id<<endl;
            beta::display();
            }
                        
private:
       int id;
       
};
class F: public beta
{
public:
      F(string s){type='f'; sentence=s;}
       void display()
            {
            //cout<<sentence<<endl;
            beta::display();
            }
                        
private:
       string sentence;


};
