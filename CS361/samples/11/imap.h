class Imap
{
public:
    Imap(){mitr = NULL;}
    int getsize(){return stuff.size();}
    void addi(string n, string i);
    void show();
    void delperson(string n);
    void delitem(string i);


private:
    multimap<string, string> stuff;
    multimap<string, string>::iterator mitr;

};

void Imap::addi(string n, string i)
    {
    stuff.insert(make_pair(n,i));
    }

void Imap::show()
    {mitr = stuff.begin();
    while(mitr!=stuff.end() )
        {
        cout<<mitr->first<<" "<<mitr->second<<endl;
        mitr++;
        }
    }

void Imap::delperson(string n)
    {
    stuff.erase(n);
    }


void Imap::delitem(string i)
    {
     multimap<string, string>::iterator temp;
    mitr=stuff.begin();
    while(mitr!=stuff.end() )
       {if(mitr->second == i){temp = mitr; mitr++; stuff.erase(temp);}
        else{mitr++;}
        }
    }