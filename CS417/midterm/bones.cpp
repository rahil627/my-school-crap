//pre-processors----------------------------------------------------------------------------------
#include <iostream>
using namespace std;

//main--------------------------------------------------------------------------------------------
int main()
{
    float result;
    for(int n=2;n!=0;n++)
    {

        for(int z=1;z<n;z++)
        {
            result=result+(z*z*z*z);//a. z*z
            result=1/result;
        }
        cout<<n<<": "<<result<<endl;
        if(n==0){system("pause");}
        if(n==100){system("pause");}
        result=0;
    }
	return 0;
}
