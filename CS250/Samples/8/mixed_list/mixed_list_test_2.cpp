#include <iostream>
#include <fstream>
using namespace std;

#include "mixed_list.h"

beta * load_data(beta * head);
void showall(beta * head);

int main()
{
beta * head= NULL;
head = load_data(head);
showall(head);
system("pause");
return 0;
}
//********************************* LOAD

beta * load_data(beta * head)
{
 D * dptr;
 E * eptr;
 F * fptr;
 char type;
 int id;
 string s; 
 fstream fin;
 fin.open("mixed_list_data.txt",ios::in);
      
//seed read
fin>>type;                                   
 while(!fin.eof() )
       {
    
       switch(type)
            {
            case 'd':{   fin>>id;
                      dptr = new D(id);
                      if(head==NULL){head = dptr;}
                      else{dptr->setnext(head);
                           head = dptr;}
                      }break;
            case 'e':{fin>>id;
                      eptr = new E(id);
                      if(head==NULL){head = eptr;}
                      else{eptr->setnext(head);
                           head = eptr;}
                      }break;
            case 'f':{fin.ignore();
                      getline(fin,s);
                      fptr = new F(s);
                      if(head==NULL){head = fptr;}
                      else{fptr->setnext(head);
                           head = fptr;
                           }
                      }break;
                        
            }
       fin>>type;
       }
  fin.close();
  return head;
  }
//*************************************SHOWALL

void showall(beta * head)
     {
     D * dptr;
     E * eptr;
     F * fptr;
     char type;
     while(head !=NULL)
          {
          type=head->gettype();
          switch(type)
             {
             case 'd': {dptr = (D *)head;
                        dptr->display();
                        }break;
             case 'e': {eptr = (E *)head;
                        eptr ->display();
                        }break;
             case 'f': {fptr = (F *)head;
                        fptr->display();
                        }break;
              }
             head = head->getnext();
             }
    }

 

