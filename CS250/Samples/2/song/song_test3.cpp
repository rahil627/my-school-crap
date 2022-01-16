#include <iostream>
#include <string>
using namespace std;

#include "song.h"

void showall(song * c);
song * addsong(song * h);
song * delsong(song * h);


char menu();

int main()
{
 song * head = NULL;
 song * curr = NULL; //short for current song
 
 char option = '7'; //a char not in the menu
while(option != 'E')
             {
             option = menu(); //option comes back from menu
             switch(option)
                   {
                   case 'A':{head = addsong(head);  }break; 
                   case 'D':{head = delsong(head);  }break;
                   case 'S':{ curr = head;
                              showall(curr);
                            }break;
                   }//end of switch

             }//eow...end of while
 


system("pause");
return 0;
}
char menu()
     {
     char option;
     cout<<"    MP3 Menu"<<endl;
     cout<<"A   add a song"<<endl;
     cout<<"D   delete a song"<<endl;
     cout<<"S   show all songs"<<endl;
     cout<<"E   exit"<<endl;
     cin>>option;
     option = toupper(option);
     return option;
     }

void showall(song * c)
     {
     while(c!=NULL)
                   {c->display();
                    c=c->getnext();
                    }
     }

song * addsong(song * h)
     {
     string t;
     cin.ignore();
     cout<<"What is song title?"<<endl;
     getline(cin, t);
     
     song * c;
     c=h;
     if(h==NULL){h=new song(t); return h;}
     while(c->getnext()!=NULL){c=c->getnext();}
     c->makenew(t);
     return h;
     }

song * delsong(song * h)
{

if(h==NULL){cout<<"WHAT?? NO SONGS!!"<<endl; return h;}
 song * target;
 song * prev;
 string t;
     cin.ignore();
     cout<<"What is song title?"<<endl;
     getline(cin, t);
//what if we are deleting the first one>
       if(t==h->gett() )
          {
          target = h;
          h = h->getnext(); //move the head;
          target->setnext(NULL);
          delete target;
          return h;
          }
//if not the first one..then find it!
     target = h;
     prev = h;
     while(t!=target->gett() )
         {
         prev = target;
         target=target->getnext();
         if(target==NULL){cout<<"NOT IN LIST"<<endl; return h;}
         }//eow
         prev->setnext(target->getnext());
         target->setnext(NULL);
         delete target;
         return h;
      }//end of delsong
          
