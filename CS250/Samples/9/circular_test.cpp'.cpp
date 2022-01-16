#include <iostream>
#include <ctime>
using namespace std;
#include "circular_node.h"

node * addnode(node * h);
node * random_node(node * h);
node * find_h(int target, node * h);
node * find_smallest(node * h);

int main()
{
int target;
srand(time(NULL));
 node * head = NULL;
 for(int i=0; i<10; i++)
 {head=addnode(head);}
 head->showall();
cout<<"choosing a random head in the circle list"<<endl;
 head=random_node(head);
 head->showall();
cout<<"choosing a specific head in the circle list"<<endl;
cout<<"target?"<<endl;
cin>>target;
 head=find_h(target, head);
 head=addnode(head);
 head->showall();
cout<<"locating smallest entry"<<endl;
 head = find_smallest(head);
 head->showall();
system("pause");
return 0;
}

node * addnode(node * h)
{
if(h==NULL){h=new node; h->setid(1); h->setnext(h);}//next points to self
else
    {h->insert(); h=h->getnext();}
return h;


}
node * random_node(node * h)
{int r=rand()%(h->count());
 for (int i=0; i<r; i++){h=h->getnext();}
return h;
}


node * find_h(int target, node * h)
{
     node * temp = h;
     if (target==h->getid()){return h;}
     h=h->getnext();
     while(h!=temp)
     {
        if(target==h->getid() ){cout<<"found"<<endl; return h;}
        h=h->getnext();
     }
   cout<<"not in circle"<<endl;
   return h;  
}
node * find_smallest(node * h)
{
 int s = h->getid();
 node * start=h;
 h=h->getnext();
 while(h!=start)
                {
                if(h->getid()<s){s=h->getid();}
                h=h->getnext();
                }
h=find_h(s,h);
return h;

 
}
