class student
{     
      public:
      student(){tuition=0;}
      void set(string n, string i, double t, string m);
      void display();

      private:
      string name;
      string id;
      double tuition;
      string middle;
      
};

void student::set(string n, string i, double t, string m)
{    
     name = n;
     id = i;
     tuition=t;
     middle = m;

}

void student::display()
{
if(name!="")
 {
 cout<<"student #: "<<id<<"  "<<name<<"   balance $"<<tuition<<endl;
 cout<<"Middle: "<<middle<<endl;
 }
}

