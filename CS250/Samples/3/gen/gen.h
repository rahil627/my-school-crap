class gen
{
public:
      gen(int i){id = i; next = NULL;}
      int getid(){return id;}
      void setnext(gen * n){next = n;}
      gen * getnext(){return next;}
      void makenew(int i){next = new gen(i);}
      void display(){cout<<"gen object #"<<setw(4)<<id<<endl;}


private:
        int id;
        gen * next;
};
