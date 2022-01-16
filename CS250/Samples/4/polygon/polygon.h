class polygon
{
 public:
    polygon(){sides=0;}
    void sets(int s){sides = s;}
    int gets(){return sides;}
    void display(){cout<<sides<<" sided polygon"<<endl;}
 protected:  //this means any inherited class has access to this data
    int sides;

};
// the : describes the relationship between triangles and polygons
class triangle : public polygon
{
 public:
      triangle(string t){sides=3; type=t;}
      void display(){
                     cout<<"this ";
                     polygon::display();
                     cout<<" is "<<type<<endl;
                     }
 private:
      string type; //acute etc..
};

