#include<iostream>
#include<cmath>
#include<iomanip>

using namespace std;

int main()
{
    int input=1000;
    double sum;
    
    
    cout<<"    MACHINE 7B    "<<endl;
    cout<<endl;
    cout<<endl;
    cout<<"S+=(1/n^4)"<<endl;
    cout<<"The machine does not get any higher than 1.0843232 with 10^3"<<endl;
    cout<<"because the exponent starts to not make a big change in the sum,"<<endl;
    cout<<"therefore it never reaches 0 because it is a summation that starts at n=1."<<endl;
    for (double n=1.0; n<=input; n++)
    {
        sum+=(1/pow(n, 4));
        //cout<<"sum: "<<sum<<endl;   
    }
    
    
    cout<<endl;
    cout<<endl;
    cout<<setprecision(8)<<sum<<endl;
    
    
    
    system ("pause");
    return 0;
}
