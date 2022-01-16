class liveW
{
 public:
      liveW();
      void display();
      void addfish(fish * n);

 private:
     fish * F[5];

};

liveW::liveW()
    {
    for(int i=0; i<5; i++){F[i]=NULL;}
    }

void liveW::display()
     {int sump=0, sumo=0;

     for(int i=0; i<5; i++)
             {
             if(F[i]!=NULL)
                {F[i]->display();
                sump=sump+F[i]->getp();
                sumo=sumo+F[i]->geto();
                if(sumo>15){sump++; sumo=sumo-16;}
                }
             
             }
     cout<<setw(20)<<"total wt: "
         <<sump<<" pounds "<<sumo<<" ounces"<<endl;

     }


void liveW::addfish(fish * n)
     {
     int i=0;
     int s=0; //location of min fish
     int min = 0; //wt of min fish
     int curr = 0; //wt of curr fish
     while(i<5)
           {
           
           if(F[i]!=NULL)
              {
              if(i==0){s=0; min=F[0]->getp()*16+F[0]->geto();}
              else{
                   curr=F[i]->getp()*16+F[i]->geto();
                   if(min> curr){s=i; min=curr;}
                   }
              i++;
              }
           else
              {
              F[i]=n;//insert new fish into free slot..then leave
              return;
              }
           }

       //at this point..we know we have 5 fish alread
       //we also know min fish wt and location of min
      delete F[s];
      F[s]=n;

     }