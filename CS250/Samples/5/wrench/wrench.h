class tool
      {
      public:
          //CONSTRUCTOR
          tool(){price = 0; next = NULL;}

          //accessors
          double getp(){return price;}
          tool * getnext(){return next;}

          //mutators
          void setp(double p){price = p;}
          void setnext(tool * n){next = n;}

          //other functions
          void display(){cout<<" $"<<price<<endl;}
          void makenew(){next = new tool;}          

      protected:
          double price;
          tool * next;

};

class wrench : public tool
{
      public:
      wrench(){size=0; boxend=false;}

      double gets(){return size;}
      bool getbe(){return boxend;}

      void sets(double s){size = s;}
      void setbe(bool b){boxend= b;}

      void display(){cout<<size<<" mm ";
                     if(boxend){cout<<"boxend wrench costs ";}
                     else{cout<<"open end wrench costs ";}
                     tool::display();
                     }

      

      private:
      double size;
      bool boxend;
      
};



