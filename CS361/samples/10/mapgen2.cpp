#include <iostream>
#include <fstream>
#include <iomanip>
#include <cmath>

using namespace std;

void show(int ** G);

int main()
{
    int r, c, i, w;
    int ** G;
    G=new int*[8];
    for(i=0; i<8; i++){G[i]=new int[8];}
    for(i=0; i<8; i++)
        {for(int j=0; j<8; j++)
             {if(i==j)G[i][j]=0;
              else{G[i][j]=9999;}
             }
        }
show(G);
system("pause");
fstream fin;
fin.open("connect_wt.txt",ios::in);
    while(!fin.eof())
       {
        fin>>r>>c>>w;
        G[r][c]=w;

        }

    show(G);
    system("pause");
    for(int k=0; k<8; k++)
        {
        for(int i=0; i<8; i++)
            {
            for(int j=0; j<8; j++)
                {
                G[i][j]=min(G[i][j], G[i][k]+G[k][j]);
                }//end of j
            }//end of i
        cout<<endl<<endl<<"iteration #"<<k<<endl;
        show(G);
        system("pause");
        }//end of k





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
             if(G[i][j]==9999)
                {cout<<setw(5)<<"*";}
             else{cout<<setw(5)<<G[i][j];}
             }
        cout<<endl;
        }

    }