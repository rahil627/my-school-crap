#include <string>
#include <iostream>
#include <iomanip> //for setw()
using namespace std;

#include "fish.h"
#include "liveW.h"

int main()
{

   fish * f;
   liveW  L;
   cout<<"no fish"<<endl;
   L.display();
   f=new fish("spotted bass", 3, 4);
   L.addfish(f);
   f=new fish("smallmouth bass", 2, 9);
   L.addfish(f);
   f=new fish("largemouth bass", 4, 4);
   L.addfish(f);
   f=new fish("spotted bass", 4, 1);
   L.addfish(f);
   f=new fish("smallmouth bass",3,1);
   L.addfish(f);
   cout<<endl<<endl<<"fish in well"<<endl;
   L.display();
   f=new fish("largemouth bass", 5, 2);
   L.addfish(f);
   cout<<endl<<endl<<"free small fry"<<endl;
   L.display();



system("pause");
return 0;
}