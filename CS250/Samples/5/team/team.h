class team
{
public:
team(){next=NULL;}
string getcoach(){return coach;}
void setcoach(string c){coach=c;}
void display(){cout<<"Coach: "<<coach<<endl;}
team* getnext(){return next;}
void setnext(team* n){next=n;}
void makenew(){next=new team;}

private:
string coach;
team*next;

};
