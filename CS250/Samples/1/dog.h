class dog
      {
      public:
      dog(string n){name = n;}
      void show(){cout<<name<<endl;}
      private:
      string name;

      };

class doghouse
      {
      public: 
      doghouse(){d=NULL;}
      void adddog(dog * D);
      void show();
      private:
      dog * d;  //this is composition..
      };
void doghouse::show()
{
 if(d!=NULL){d->show(); cout<<" is in the doghouse"<<endl;}
else
    {cout<<"alas..no dogs in the doghouse"<<endl;}
}

void doghouse::adddog(dog * D)
     {
     d=D;
}
