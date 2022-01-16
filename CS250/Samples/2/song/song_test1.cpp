#include <iostream>
#include <string>
using namespace std;

#include "song.h"

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
 head->display();
 curr->display();

 


system("pause");
return 0;
}
