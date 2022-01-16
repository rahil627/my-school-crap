class alpha
    {public: 
        alpha(int i){id =i;
        x =  (double(rand()%200)/10)-10;
        y =  (double(rand()%200)/10)-10;
        } 
        void display(){cout<<id<<" -> ";}
        double getx(){return x;}
        double gety(){return y;}
    private: 
        int id;
        double x,y;
    };

class beta
    {public: 
        beta(char c){id =c;
                    x =  (double(rand()%200)/10)-10;
                    y =  (double(rand()%200)/10)-10;
                    } 
        void display(){cout<<id<<" at ("<<x<<", "<<y<<")"<<endl;}
        bool distance(list <alpha>::iterator aitr);
    private: 
        char id;
        double x,y;
    };


bool beta::distance(list <alpha>::iterator aitr)
    {
     double d, dx, dy;
     dx=x-aitr->getx();
     dy=y-aitr->gety();
     d=sqrt(pow(dx,2) + pow(dy,2) );
     if(d<5){return true;}
     else{return false;}
     

    }
