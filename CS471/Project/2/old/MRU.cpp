//reads the virtual addresses, converts it into page references, and stores it in a queue (PRQ).
//as the algorithm reads PRQ, it puts the used PR's into a stack
//therefore the top of the stack is the target for page replacement

#include <iostream>
#include <fstream>
#include <queue>
#include <stack>
#include <vector>
#include <algorithm>

using namespace std;

int main()
{
    //run 1
    int pageSize=512;
    int noOfFrames=3;

    //init
    //int table[100];//page table - ex. table[page] contains the frame, assume size = infinite?
    int MM[noOfFrames];/*[pageSize];*///memory allocated to the process - ex. mem[frame][offset] contains the physical address
    for(int i=0; i<noOfFrames; i++)
    {
        MM[i]=-1;//NULL doesn't work for some reason, so -1 is "empty" because page 0 exists
    }


    //read in virtual addresses
    fstream fin;
    fin.open("data.txt",ios::in);
	if (!fin){cout<<"Could not find file =(\n";return 0;}

    queue<int> PRQ;//page reference queue
    int t;//temp

	while(!fin.eof())
	{
        fin>>t;
        fin.ignore(100,'\n');
        t=t/pageSize;
        PRQ.push(t);
        if(isdigit(fin.peek())==false){fin.ignore(100,'\n');}//ignores the last line
	}
    fin.close();
    /*
    //read test
    cout<<"page reference queue"<<endl;
    while(PRQ.empty()==false)
    {
        cout<<PRQ.front()<<",";
        PRQ.pop();
    }
    cout<<endl;
    */

    /*
    //virtual->physical address conversion - not necessary for the program
    //to test the first data (514)
    table[1]=3;//putting frame 3 into page 1

    int VA = VAqueue.front();
    VAqueue.pop();

    int page=VA/pageSize;

    int offset=VA%pageSize;
    //return the frame in that page
    int frame=table[page];
    //then return the phyiscal address using the frame and offset
    int PA=(frame*pageSize)+offset;//not necessary for the program
    cout<<PA;
    */

    bool fault;//page fault
    int noOfPages=PRQ.size();
    double noOfPageFaults=0;
    double noOfReads=PRQ.size();//number of virtual addresses in the file/program reads

    stack<int> PRS;//stack of the used page references
    /*
    vector<int> PRV;
    vector<int>::iterator result;
    vector<int>::iterator itbegin;//oldest
    vector<int>::iterator itend;
    bool flag=false;
    */
    int targetValue;
    int targetIndex;

    while(PRQ.empty()==false)
    {
        fault=true;//reset

        for(int i=0; i<noOfFrames; i++)//check the MM
        {
            if(PRQ.front()==MM[i]){fault=false;}//if the page exists, no fault
        }

        if(fault==false)
        {
            PRS.push(PRQ.front());//1

            PRQ.pop();
            noOfPages--;
        }


        else//fault==true
        {
            //if the frame is empty
            for(int i=0; i<noOfFrames; i++)
            {
                if(MM[i]==-1)
                {
                    noOfPageFaults++;
                    MM[i]=PRQ.front();
                    PRS.push(PRQ.front());//2
                    PRQ.pop();
                    i=noOfFrames;//exit loop
                    fault=false;//skip next part
                }
            }

            if(fault==true)//skip or not?
            {
                noOfPageFaults++;

                targetValue=PRS.top();

                for(int i=0; i<noOfFrames; i++)
                {
                    if(MM[i]==targetValue)
                    {
                        MM[i]=PRQ.front();
                        PRS.push(PRQ.front());//3
                        PRQ.pop();
                    }
                }
            }
        }


    }

    //display end MM and statistics
    for(int i=0; i<noOfFrames; i++){cout<<MM[i]<<endl;}
    cout<<"\nnumber of pages: "<<noOfPages<<endl;
    cout<<"number of page faults: "<<noOfPageFaults<<endl;
    double pageFaultPercentage=(noOfPageFaults/noOfReads)*100;
    cout<<"page fault percentage: "<<pageFaultPercentage<<"%"<<endl;

    //put everything in functions!

    return 0;
}
