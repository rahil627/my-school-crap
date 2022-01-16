class edge
{
    private:
        char dest;//destination or ending node
        int weight;
    public:
        //edge(char d, int w){dest=d;weight=w;}
        edge(){dest='\0'; weight=0;}//default char?
        void set(char d, int w){dest=d;weight=w;}
        char getdest(){return dest;}
        void display()	{cout<<"\t"<<setw(2)<<weight<<"->"<<dest;}
};
class edgelist
{
    private:
        list <edge> elist;
        list <edge>::iterator eitr;
    public:
        edgelist(){eitr=NULL;}
        bool checkedge(char d)
        {
            eitr=elist.begin();
            if(eitr==NULL){return 0;}
            else
            {
                while (eitr!=elist.end())
                {
                if(eitr->getdest()==d){return 1;}
                else{eitr++;}
                }
            }
        }
        list<edge>::iterator getedge(char d)
        {
            list<edge>::iterator target;
            eitr=elist.begin();
            if(eitr==NULL){return target;}
            else
            {
                while (eitr!=elist.end())
                {
                if(eitr->getdest()==d){return target;}
                else{eitr++;}
                }
            }
        }
        //set eitr to begin
        void display();
        void add(edge e){elist.push_back(e);}
        void del(char d)
        {
            list<edge>::iterator target;
            eitr=elist.begin();
            if(eitr!=NULL)
            {
                while (eitr!=elist.end())
                {
                if(eitr->getdest()==d){elist.erase(target);}
                else{eitr++;}
                }
            }
        }
};
class universe
{
    private:
        map <char,edgelist> nmap;//n=node
        map <char,edgelist>::iterator nitr;
    public:
        universe(){nitr=NULL;}
        void display();
        //map<char,edgelist>::iterator getnitr(){return nitr;}// {aVector.begin();
        void addnmap(char s, edgelist e){nmap.insert(make_pair(s,e));}
        void addedge(char s, char d, int w)//if edge A->B exists, then don't make B->A
        {
            nitr=nmap.find(s);
            edge tedge;
            tedge.set(d,w);
            //edge *pedge;
            //pedge=new edge(d,w);
            nitr->second.add(tedge);
        }
        //map<char,edgelist>::iterator find(char s){nitr = nmap.find(s); return nitr;}
        bool checkedge(char s, char d)
        {
            nitr=nmap.find(s);
            return nitr->second.checkedge(d);
        }
        int getsize(){return nmap.size();}
        void deledge(char s, char d);
        //void delnmap(int i){edge.erase(i);}
        void matrix(int n);
};
void edgelist::display()
{
    eitr=elist.begin();
    if(eitr!=NULL)
    {
        while (eitr!=elist.end())
        {
            eitr->display();
            eitr++;
        }
    }
}
void universe::display()
{
    cout<<"nodes\tedges\n";
	nitr = nmap.begin();
	while(nitr!=nmap.end())
	{
	    cout<<nitr->first;
	    nitr->second.display();
	    cout<<endl;
		nitr++;
	}
}
void universe::deledge(char s, char d)
{
	//nitr = nmap.begin();
    nitr=nmap.find(s);
    nitr->second.del(d);
}
void universe::matrix(int n)
{
    cout<<"     ";
    for(int i=0; i<n; i++){cout<<i<<"    ";}
    cout<<"\n\n";

    for(int i=0; i<n; i++)
    {
        cout<<i;
        for(int j=0; j<n; j++)
        {
            cout<<setw(5)<<"*";//if edge is empty
            //else cout the weight
        }
        cout<<endl;
    }
}

