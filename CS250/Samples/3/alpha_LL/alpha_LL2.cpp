#include <string>
#include <fstream>
#include <iostream>
using namespace std;
#include"alpha_LL.h"

alpha * load(string fname);
void show(alpha * h);
alpha * cleanup(alpha * h);
alpha * delone(alpha * h);
alpha * addone(alpha * h);
void update(alpha * h);

int main()
{   alpha * head=NULL;

    string fname;
    cout<<"what file?"<<endl;
    getline(cin,fname);
    

     head = load(fname);
     show(head);
     head=delone(head);
     head=addone(head);
     show(head);
     update(head);
     head = cleanup(head);
    
    
 system("pause");
return 0;
}

alpha * load(string fname)
{
alpha * h=NULL;
alpha * curr;
fstream fin;
        fin.open(fname.c_str(),ios::in);
        int i;
        fin>>i;
        while(!fin.eof() )
        {
        if(h==NULL){h=new alpha(i); curr=h;}
        else{
             curr->makenew(i);
             curr=curr->getnext();
             }
        fin>>i;
        }
fin.close();
return h;
}
void show(alpha * h)
{    
     if(h==NULL){cout<<"no data in file"<<endl; return;}
     
     while(h!=NULL)
     {h ->display();
      h = h->getnext();
      }

}

alpha * cleanup(alpha * h)
     {
     alpha * c;
     while(h!=NULL)
     {
     c=h;
     h=h->getnext();
     c->setnext(NULL);
     delete c;
     }
     return NULL;
     }


alpha * delone(alpha * h)
{
 alpha * target;
 alpha * prev;
 int val;
 cout<<"delete which id?"<<endl;
 cin>>val;

 //special case of new head
 if(h->getid()==val)
                    {target = h;
                     h= h->getnext();
                     target->setnext(NULL);
                     delete target;
                     }
 else{
      target=h->getnext();
      prev=h;
      while(target->getid()!=val)
           {prev = target;
            target=target->getnext();
            if(target==NULL){cout<<"sorry..not found"<<endl; return h;}
            }
      prev->setnext(target->getnext());
      target->setnext(NULL);
      delete target;
      }
return h;
}

alpha * addone(alpha * h)
{
 //this function will be push-front
 alpha * newguy;
 int i;
 cout<<"what is newguy's id?"<<endl;
 cin>>i;
 newguy=new alpha(i);

 newguy->setnext(h);
 h=newguy;
 return h;

}


void update(alpha * h)
{
alpha * curr=h;
string fname;
cin.ignore();
cout<<"save under what filename?"<<endl;
getline(cin,fname);
fstream fout;
fout.open(fname.c_str(),ios::out);
    while(curr!=NULL)
    {if(curr!=h){fout<<endl;}
     fout<<curr->getid();
     curr=curr->getnext();
     }
fout.close();
}