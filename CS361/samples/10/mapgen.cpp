#include <iostream>
#include <fstream>
#include <iomanip>
#include <cmath>
using namespace std;

void show(int ** G);

int main()
{
    int r, c, i;
    int ** G;
    G=new int*[8];
    for(i=0; i<8; i++){G[i]=new int[8];}
    for(i=0; i<8; i++)
        {for(int j=0; j<8; j++)
             {if(i==j)G[i][j]=1;
              else{G[i][j]=0;}
             }
        }  
fstream fin;
fin.open("connect.txt",ios::in);
    while(!fin.eof())
       {
        fin>>r>>c;
        G[r][c]++;

        }

    show(G);

for(r=0; r<8; r++)
{
for(c=0; c<8; c++)
    {
    if(G[r][c]!=0)
       {
        for(i=0; i<8; i++)
            {
            if(G[c][i]!=0)
                {
                if(G[r][i]==0){G[r][i]=G[r][c]+G[c][i];}
                else{
                    G[r][i]=min(G[r][i], G[r][c]+G[r][i]);
                    }//end of else
                }//end of if
            }//end of i loop
    
        }//end of if    



    }//end of c loop
}//end of r loop

cout<<"evaluation complete"<<endl;
show(G);
system("pause");
return 0;
}

void show(int **G)
    {
   for(int i=0; i<8; i++)
        {for(int j=0; j<8; j++)
             {
             cout<<setw(4)<<G[i][j];
             }
        cout<<endl;
        }  

    }