class alpha
{public:
        alpha(int i){ID=i; next = NULL;}
        int getid(){return ID;}
        
        void setnext(alpha *n){next = n;}
        alpha * getnext(){return next;}
        void makenew(int i){next = new alpha(i);}
        
        void display(){cout<<"I AM ALPHA # "<<ID<<endl;}

        ~alpha(){cout<<"ALPHA #"<<ID<<" DESTROYED"<<endl;}
               

 private:
       int ID;
       alpha * next;
}; 
