#include <iostream>
#include <fstream>
using namespace std;

int main()
{

fstream fout;
fout.open("data3.txt",ios::out);

    int i, j, k=0;
for(i=0; i<10; i++)fout<<" "<<i;
fout<<endl;

    for(i=0; i<10; i++)
    {fout<<i<<" ";
    for(j=0; j<10; j++)
        {k=i+j; fout<<" "<<k;}
    fout<<endl;}
fout.close();
    


system("pause");
return 0;
}