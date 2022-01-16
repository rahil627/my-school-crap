//mapping a string to a class rather than an int
class inside
{
public:
	inside(){I=0;}
	void seti(int i){I=i;}
	int geti(){return I;}
	void display(){cout<<"Internal obj has I = "<<I<<endl;}
private:
	int I;
};

class atlasmm
{
public:
	atlasmm();
	void addm(string S, inside i);
	void showmap();
	int getsize();
	void delm(string T);
	void dels(string T, int i);

private:
	 multimap <string, inside>map1;
	 multimap <string, inside>::iterator mitr;
};
atlasmm::atlasmm()
{
	mitr=NULL;
}

void atlasmm::delm(string T)
{
	map1.erase(T);
}


void atlasmm::dels(string T, int i)
{
	multimap <string, inside>::iterator target;	
	mitr = map1.begin();
	while(mitr!=map1.end())
	{
		if(mitr->first == T)
		{
			if(mitr->second.geti()==i)
			{	
				target = mitr;
				mitr++;
				map1.erase(target);
			}
            
		}
		 mitr++;
	}
}
void atlasmm::addm(string S, inside i)
{
	map1.insert(make_pair(S,i));//inserts into the multimap
}

void atlasmm::showmap()
{
	mitr = map1.begin();
	while(mitr!=map1.end())
	{
		cout<<mitr->first<<" --> "; mitr->second.display();
		mitr++;
	}
}

int atlasmm::getsize()
{
	return map1.size();
}
