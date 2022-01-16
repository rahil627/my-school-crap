struct wt
{int lb;
 int oz;
};

class fish
{
 public:
 fish(string s, int p, int o)
             {species = s;
              W.lb = p;
              W.oz = o;
              }

 ~fish(){cout<<"ahh..catch and release"<<endl;}
 int getp(){return W.lb;}
 int geto(){return W.oz;}

 void display();

 private:
 string species;
 wt W;

};

void fish::display()
     {
      cout<<"this "<<species<<" weighs ";
      cout<<W.lb<<" lbs and "<<W.oz<<" ounces"<<endl;
     }
