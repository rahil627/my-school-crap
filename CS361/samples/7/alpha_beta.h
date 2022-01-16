class alpha
{
    public: 
         alpha(char c){C=c;}
         void ink(int i){C=C+i;}
         void display(){cout<<"alpha: "<<C<<endl;}
    private:
    char C;
};

class beta 
{
    public:
    beta(int i){id=i;}
    void adda(alpha * a){head.push_back(a);}
    void showall();

    private:
    int id;
    list<alpha *>head;
    list<alpha *>::iterator aitr;
   
};

void beta::showall()
{
    aitr=head.begin();
    while(aitr!=head.end())
        {(*aitr)->display();
         aitr++;
        }

}










