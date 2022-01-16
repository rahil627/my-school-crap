#include <iostream>
using namespace std;

#include "animal.h"
//  void editR(reptile & R);
//since we are passing the address of the original
//we are not editing a copy..no return is required.
reptile editR(reptile R);
int main()
{
 cout<<"        THE ANIMAL!"<<endl;
 animal A;
 A.setL(2); 
 A.display();

 cout<<endl<<endl<<"......... THE REPTILE!"<<endl;
 reptile R;
 cout<<"--------------call edit function"<<endl;
 R=editR(R);

 cout<<"****************  THE SNAKE!!"<<endl;
 snake S;

 S.seto(false);
 S.setv(true);
 S.display();

system("pause");
return 0;
}

reptile editR(reptile R)
     {
     int L;
     bool ans1;
     cout<<"oviparious? enter 0 for no, 1 for yes"<<endl;
     cin>>ans1;
     R.seto(ans1);
     cout<<"Legs?"<<endl;
     cin>>L;
     R.setL(L);
     R.display();
     cout<<"-------------------------"<<endl;
     return R;
     }
