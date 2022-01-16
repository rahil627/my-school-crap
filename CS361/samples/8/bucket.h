class bucket
{
public:
    bucket(){x=0; contents=0;}
    bucket(int i){contents=0; x=i;}
    void fill(){contents++;}
    void empty(){contents=0;}
    void display(){cout<<contents<<" ";}
private:
    int contents;
    int x; //location
};

class target
{
    public:
    target(){x=0;}
    void move(){x++;}
    void display(){cout<<"target at "<<x<<endl;}
    private:
    int x;

};
