/*
reads the virtual addresses, converts it into page references, and stores it in a queue (PRQ).
*/
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
    int noOfFrames=4;

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
        //if(t>pageSize*noOfFrames)don't put it in PRQ and cout<<"out of bounds";
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
    bool fault;//page fault
    int noOfPages=PRQ.size();
    double noOfPageFaults=0;
    double noOfReads=PRQ.size();//number of virtual addresses in the file/program reads

    while(PRQ.empty()==false)
    {
        for(int i=0; i<noOfFrames; i++)
        {
            fault=true;
            for(int j=0; j<noOfFrames; j++)//check the MM
            {
                if(PRQ.front()==MM[j]){fault=false;}//if the page exists, no fault
            }

            if(fault==false){PRQ.pop();noOfPages--;i--;}//pop & don't move node in MM

            else//fault==true
            {
                noOfPageFaults++;

                MM[i]=PRQ.front();

                PRQ.pop();
                cout<<"page fault!"<<endl;for(int i=0; i<noOfFrames; i++){cout<<MM[i]<<endl;}cout<<endl;//display
            }

            if(PRQ.empty()==true)i=noOfFrames;//sexyfix!
        }
    }

    //display end MM and statistics
    //for(int i=0; i<noOfFrames; i++){cout<<MM[i]<<endl;}
    cout<<"\nnumber of pages: "<<noOfPages<<endl;
    cout<<"number of page faults: "<<noOfPageFaults<<endl;
    double pageFaultPercentage=(noOfPageFaults/noOfReads)*100;
    cout<<"page fault percentage: "<<pageFaultPercentage<<"%"<<endl;

    //put everything in functions!

    return 0;
}
