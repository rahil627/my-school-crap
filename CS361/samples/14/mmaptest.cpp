#include <iostream>
#include <map>
using namespace std;
class inmap
{
    public:
    inmap(){}
    inmap(char c, int p){inM[c]=p;}
   
    void display(){mitr=inM.begin();
                   cout<<mitr->first<<" "<<mitr->second<<endl;}
    private:
    map<char,int>inM;
    map<char,int>::iterator mitr;
};

int main()
{
    inmap * Iptr;

    map <char, inmap >::iterator outside;
    map <char, inmap >map1;
    Iptr = new inmap('f',10);
    Iptr->display();
    map1['a']=*Iptr;
    
    
    outside = map1.begin();
    while(outside!=map1.end())
        {
        cout<<outside->first<<"------";
        Iptr=&outside->second;
        Iptr->display();
        outside++;
        }
    system("pause");
    return 0;

}