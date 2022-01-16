

class block
{
public:  

       block(int x){X=x; type = 'b';}
       char gettype(){return type;}
       void display(){cout<<"My id = "<<X<<endl;}

protected:
        block(){X=0;}  
        char type;
        int X;
};

class rectangle: public block
{//when a rectangle is created, it will make a block using 
//the default constructor above
public:
       rectangle(int w, int h, int x){W=w; H=h; X=x;
                                     type = 'r';}
       void display(){cout<<W<<" by "<<H<<" by "<<X<<" rectangle"<<endl;}
private:
        int W;
        int H;

};
