/*
reads the virtual addresses, converts it into page references, and stores it in a queue (PRQ).
1) if the page exists in MM, do nothing
2) if the MM is empty, fill it in!
3) if the MM is full, replace page according to the algorithm
LRU
it checks past page references from the most recent to the least recent
if it exists in the MM then found[frame]=true.
the last page reference (that matches MM) found is the page to replace
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

    vector<int> PRV;//vector of the used page references
    PRV.push_back(-2);//this is the value for PRV.begin()

    vector<int>::iterator it;
    /*
    vector<int>::iterator result;
    vector<int>::iterator itbegin;//oldest
    vector<int>::iterator itend;
    bool flag=false;
    */
    int targetValue=69;//page to replace
    int targetIndex;//or frame to replace

    bool found[noOfFrames];
    int noOfFound=0;

    while(PRQ.empty()==false)
    {
        fault=true;//reset

        for(int i=0; i<noOfFrames; i++)//check the MM
        {
            if(PRQ.front()==MM[i]){fault=false;}//if the page exists, no fault
        }

        if(fault==false)
        {
            PRV.push_back(PRQ.front());//1

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
                    PRV.push_back(PRQ.front());//2
                    PRQ.pop();
                    i=noOfFrames;//exit loop
                    fault=false;//skip next part
                }
            }

            if(fault==true)
            {
                noOfPageFaults++;

                //the agorithm to find the target
                for(int j=0; j<noOfFrames; j++)found[j]=false;//reset
                targetValue=-3;

                for(it=PRV.end()-1;*it!=-2;it--)//from the current point to the back
                {
                    //cout<<*it;//testing purposes
                    for(int i=0; i<noOfFrames; i++)
                    {
                        if(*it==MM[i])//compare each frame to the current page reference
                        {
                            found[i]=true;

                            noOfFound=0;

                            //if(found[]==true)targetValue=*it;
                            for(int j=0; j<noOfFrames; j++){if(found[j]==true)noOfFound++;}//noOfFound is always 1 for some reason

                                                        if(targetValue==-3){//yea sloppy...i couldnt find a way to exit the loop properly =(

                            if(noOfFound==noOfFrames){targetValue=*it/*or MM[i]*/;i=noOfFrames;*it=-2;}//exit the loop! so this never happens
                                                        }
                        }
                    }
                }
                //end of algorithm

                cout<<"here's where the algorithm kicks in!"<<endl;
                cout<<"target page: "<<targetValue<<endl;
                cout<<"replace with: "<<PRQ.front()<<endl;

                //replace target
                for(int i=0; i<noOfFrames; i++)
                {
                    if(MM[i]==targetValue)
                    {
                        cout<<"target frame: "<<i<<endl;
                        MM[i]=PRQ.front();
                        PRV.push_back(PRQ.front());//3
                        PRQ.pop();
                    }
                }
            }
        }
        for(int i=0; i<noOfFrames; i++){cout<<MM[i]<<endl;}cout<<endl;
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
