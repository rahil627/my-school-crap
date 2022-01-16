class card
{
 public:
      card(){ indeck = true; r=rand();}
      void setn(char n){name = n;}
      void display(){cout<<"I am the "<<name<<" card"<<endl;
                     cout<<"My random value: "<<r<<endl;}
      void setr(int R){r=R;}             
      int getr(){return r;}
      bool getindeck(){return indeck;}
      void remove(){indeck = false;}
 private:
    char name;
    int r;
    bool indeck;
};
