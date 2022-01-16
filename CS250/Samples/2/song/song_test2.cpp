#include <iostream>
#include <string>
using namespace std;

#include "song.h"

void showall(song * c);

int main()
{
 song * head;
 song * curr; //short for current song
 head = new song("Everythings coming up roses");
 curr = head;
 curr->makenew("Let it Be");
 curr=curr->getnext();
 curr->makenew("Oh the Joys of Maidenhood");
 curr=curr->getnext();

 curr=head; //start all over please
 showall(curr);

 


system("pause");
return 0;
}


void showall(song * c)
     {
     while(c!=NULL)
                   {c->display();
                    c=c->getnext();
                    }
     }
