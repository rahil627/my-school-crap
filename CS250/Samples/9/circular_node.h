class node
{
public:
       node(){next = NULL;}
       void setid(int i){id = i;}
       int getid(){return id;}
       void display(){cout<<"id: "<<id<<endl;}
       void setnext(node * n){next = n;}
       node * getnext(){return next;}
       void makenew(){next = new node;}
       int count();
       void insert();
       void showall();

private:
        int id;
        node * next;

};


void node::insert()
     {
     node * newn;
     newn = new node;
     int i=count();
     i++;
     newn->setid(i);
     newn->setnext(next);
     next=newn;
     }


void node::showall()
     {
     node * curr = next;
     display();
     while(curr!=this)
           {
           curr->display();
           curr=curr->getnext();
           }
     }

int node::count()
{
 node * curr = next;
 int i=1;
 while(curr!=this){curr=curr->getnext(); i++;}
return i;
}


