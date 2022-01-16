class animal
{
//private:
protected:
int legs;

public:
       animal(){}
       void setL(int L){legs=L;}
       void display()
            {cout<<"has "<<legs<<" legs"<<endl;}
};


class reptile: public animal
{

protected:
      bool ovi; 
public:
      reptile(){}
//      void setL(int L){  animal::setL(L);  }
      void seto(bool o){ovi = o;}
      void display()
          {cout<<"this reptile is ";
           if(ovi){cout<<"egg laying "<<endl;}
           else{cout<<"not egg laying"<<endl;}
           animal::display();
           }        
};

class snake : public reptile
{
 private:
     bool venom;
 public:
     snake(){legs=0;}
     void setv(bool v){venom=v;}
     void display()
     {reptile::display();
      if(venom){cout<<" is poisonous"<<endl;}
      else{cout<<" is not poisonous"<<endl;}
      }
 

};





