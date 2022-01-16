class target
{
    private:
        double x;
        double y;
        char type;
        int noise;
        //thermalnoise, radionoise, acousticnoise?
    public:
        target(){x=50;y=50;type='A';noise=200;}
        void move(int xm, int ym){x=x+xm;y=y+ym;}//x-move, y=move
        void display(){cout<<"Target is actually at ("<<x<<","<<y<<")"<<" and it's noise is "<<noise<<endl;}
        void gettype(char t){type=t;}
        void makenoise(list<sensor>::iterator sitr)//return?
        {
            if(sitr->getx()>x-2.5&&sitr->gety()>y-2.5&&sitr->getx()<x+2.5&&(sitr->gety()<y+2.5))//5x5 area
            {
                double distance;
                distance=sqrt(pow((sitr->getx()-x),2)+pow((sitr->gety()-y),2));//distance formula
                sitr->settype(type);
                sitr->setnoise(noise/distance);//noise divided by .5 distance would multiply..fix it? nah
            }
        }
};
