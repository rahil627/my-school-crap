class alpha
{
public:
    alpha(ofstream * fout, int i);
private:
    int id;

};

alpha::alpha(ofstream * fout, int i)
{
    id = i;
    if(fout==NULL)
       {fout=new ofstream;
        fout->open("class_id.txt",ios::app);
        }
    (*fout)<<id<<endl;

}