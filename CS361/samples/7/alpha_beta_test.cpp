#include <iostream>
#include <list>
using namespace std;
#include "alpha_beta.h"

int main()
{
list <alpha> alist;
list <alpha>::iterator aitr;

alpha * newa;
char L='A';
for(int i=0; i<6; i++)
    {
    newa=new alpha(L);
    L++;
    alist.push_back(*newa);
    }

//do we have a list of alphas?
aitr= alist.begin();
while(aitr!=alist.end())
{
    aitr->display();
    aitr++;
}

beta B1(7);
beta B2(15);
alpha * aptr;
aitr=alist.begin();
while(aitr!=alist.end())
{
   aptr = &(*aitr);
   B1.adda(aptr);
   if(aitr==alist.begin()){B2.adda(aptr);}
    aitr++;
    aptr=&(*aitr);
   B2.adda(aptr);
    aitr++;
}
cout<<"testing our beta objects"<<endl;
B1.showall();
cout<<endl;
B2.showall();

aitr=alist.begin();
while(aitr!=alist.end())
    {aitr++;
     aitr->ink(3);
     aitr++;}


cout<<endl<<"testing our beta objects after ink"<<endl;
B1.showall();
cout<<endl;
B2.showall();

aitr=alist.begin();
aitr->ink(10);

cout<<endl<<"testing to make sure both Beta's see the same change"<<endl;
B1.showall();
cout<<endl;
B2.showall();

system("pause");
return 0;
}
