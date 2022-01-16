#include <iostream>
#include <list>
#include <cmath>
using namespace std;
#include "alpha.h"
int main()
{
   list <alpha> Alist;
   list <beta> Blist;
   list <alpha>::iterator aitr;
   list <beta>::iterator bitr;
   bool close_enough = false;

   alpha * aptr;
   beta * bptr;
   char Let='A';

   for(int i=0; i<10; i++)
      {
       aptr=new alpha(i);
       Alist.push_back(*aptr);
       bptr = new beta(Let);
       Blist.push_back(*bptr);
       Let++;
      }

    aitr=Alist.begin();
    while(aitr!=Alist.end() )
         {
         bitr=Blist.begin();
         while(bitr!=Blist.end() )
               {
                close_enough=bitr->distance(aitr);
                if(close_enough)
                    {
                    aitr->display();
                    bitr->display();
                    }
                close_enough=false;
                bitr++;
                } 
        aitr++;
         }





system("pause");
return 0;
}