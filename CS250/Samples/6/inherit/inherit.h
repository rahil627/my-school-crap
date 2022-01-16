class brown
{
 public:
       
        brown(int t=0, int i=0){next = NULL; type = t; id = i;}
        void display(){cout<<"brown object #"<<id<<endl;
                       cout<<"is really type "<<type<<endl;
                       }
        int gettype(){return type;}
        void setnext(brown * n){next = n;}
        brown * getnext(){return next;}
        
        void makenew(int t, int i){next= new brown(t,i); }        

 protected:
     int type;
     int id;
     brown * next;
};

class red : public brown
{
 public:
       red(int i, char l){type = 1; id=i; L=l; }
       void display(){cout<<"I am a red object: "<<L<<endl;
                      brown::display();}
 private:
       char L;
};

class black : public brown
{
 public:
       black(int i, char l){type = 2; id=i; L=l; }
       void display(){cout<<" I am a black object: "<<L<<endl;
                      brown::display();}
 private:
       char L;
};
