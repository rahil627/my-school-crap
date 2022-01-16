#include <iostream>
#include <fstream>
using namespace std;
#include "alpha_fstream.h"

void f1(ofstream * fout);
void f2(ofstream * fout);
void f3(ofstream * fout);

int main()
{
    ofstream * fout=NULL;
    alpha A1(fout,3);
    fout = new ofstream;
    fout->open("testing.txt",ios::app);

    f1(fout);
    f2(fout);
    f3(fout);
    alpha A2(fout,5);

    

system("pause");
return 0;
}

void f1(ofstream * fout)
{
    (*fout)<<"this is f1"<<endl;
}
void f2(ofstream * fout)
{
    (*fout)<<"this is f2"<<endl;
}


void f3(ofstream *fout)
{
    fout->close();
    fout->open("test2.txt",ios::out);
    (*fout)<<"data from f3"<<endl;
}