class Imap
{
public:
    Imap(){mitr = NULL;}
    int getsize(){return node.size();}
    void addi(string n, int i);
    void delperson(string n);
    void show();
    //void delitem(int i);


private:
    multimap<string, int> node;
    multimap<string, int>::iterator mitr;

};

void Imap::addi(string n, int i)
    {
    node.insert(make_pair(n,i));
    }

void Imap::show()
    {mitr = node.begin();
    while(mitr!=node.end() )
        {
        cout<<mitr->first<<" "<<mitr->second<<endl;
        mitr++;
        }
    }

void Imap::delperson(string n)
    {
    node.erase(n);
    }

/*
void Imap::delitem(string i)
    {
     multimap<string, string>::iterator temp;
    mitr=node.begin();
    while(mitr!=node.end() )
       {if(mitr->second == i){temp = mitr; mitr++; node.erase(temp);}
        else{mitr++;}
        }
    }
*/
