class song
{
 public:

 song(){next=NULL;}
 song(string t){title = t; next = NULL;}

 void sett(string t){title=t;}
 void setnext(song * n){next = n;}
 
 string gett(){return title;}
 song * getnext(){return next;}

 void makenew(){next = new song;}
 void makenew(string t){next = new song(t);}

 void display(){cout<<title<<endl;}
 
 private:
 string title;
 song * next;

};
