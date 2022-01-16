 //  S = S0 * e^(-D/tau)

#include <iostream>
#include <cmath>
#include <fstream>
using namespace std;

int main()
{
    //D = distance from source
    //S0 = initial source strenght (signal)
    //S = current signal at D
    //tau = 2 (half life)
fstream fout;
fout.open("outdata.txt",ios::out);
double S0 = 100;
double S, D=0, tau=2;
for(int i=0; i<25; i++)
    {
   
    S=S0*pow(2.718,(-1.0*D/tau));
    fout<<D<<'\t'<<S<<endl;
    D=D+1;
    }

fout.close();
system("pause");
return 0;
}