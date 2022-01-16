/*
//Problem 2 : Project for CS 471
reads the virtual addresses, converts it into page references, and stores it in a queue (PRQ).
1) if the page exists in MM, do nothing
2) if the MM is empty, fill it in!
3) if the MM is full, replace page according to algorithm

FIFO
i gimped this one. it just loops through MM, 123412341234 and just replaces in that order

MRU
as the algorithm reads PRQ, it puts the used PR's into a stack
therefore the top of the stack is the target for page replacement

LRU
as the algorithm reads PRQ, it puts the used PR's into a vector (PRV)
it checks past page references (PRV) from the most recent (PRV.end) to the least recent (PRV.begin)
if it exists in the MM then found[frame]=true.
the last page reference that matches MM found is the page to replace (target)

OPT - just look at it as a backwards LRU, or LRU in the future!!!
first, PRQ has to be changed to a container that can be iterated through such as a list or vector.
then you can just flip around my LRU algorithm to check from the current point to the frontend of the container

or do whatever you like! =P
*/

#include <iostream>
#include <fstream>
#include <queue>
#include <stack>
#include <vector>
#include <algorithm>
#include <string>

using namespace std;

queue<int> read(int pageSize);
void display(int pageSize, int noOfFrames, string algorithm, double pageFaultPercentage);
void FIFO(int pageSize, int noOfFrames);
void LRU(int pageSize, int noOfFrames);
void MRU(int pageSize, int noOfFrames);
void OPT(int pageSize, int noOfFrames);

int main()
{
    cout<<"Page Size    # of Frames    PR Algorithm        Page Faults % "<<endl;

    //main inits
    int page_size[3]={512,1024,2048};
    int frame_size[3]={4,8,12};
    int p, f;

    //queue<int> PRQ?
    for(int i=0; i<3; i++)//page size
    {
        p=page_size[i];
        //PRQ=read(p)?
            for(int j=0; j<3; j++)//frame size
        {
            //init MM?
            f=frame_size[j];
            FIFO(p,f);
            LRU(p,f);
            MRU(p,f);
            OPT(p,f);
            system("pause");
        }
    }

    //maybe a conclusion? add up the totalFaultPercentages

    return 0;
}

queue<int> read(int pageSize)
{
    //read in virtual addresses
    fstream fin;
    fin.open("data.txt",ios::in);
	if (!fin){cout<<"Could not find file =(\n"; system("pause");}

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
    return PRQ;
}

void display(int pageSize, int noOfFrames, string algorithm, double pageFaultPercentage)
{
    cout<<pageSize<<"          "<<noOfFrames<<"              "<<algorithm<<"                "<<pageFaultPercentage<<"%"<<endl;
}

void FIFO(int pageSize, int noOfFrames)
{
    queue<int> PRQ = read(pageSize);

    //init
    int MM[noOfFrames];
    for(int i=0; i<noOfFrames; i++)MM[i]=-1;//NULL doesn't work for some reason, so -1 is "empty" because page 0 exists

    bool fault;//page fault?
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

            if(fault==false){PRQ.pop();i--;}//pop & don't move node in MM

            else//fault==true
            {
                noOfPageFaults++;

                MM[i]=PRQ.front();

                PRQ.pop();
                //cout<<"page fault!"<<endl;for(int i=0; i<noOfFrames; i++){cout<<MM[i]<<endl;}cout<<endl;//display
            }

            if(PRQ.empty()==true)i=noOfFrames;
        }
    }

    //for(int i=0; i<noOfFrames; i++){cout<<MM[i]<<endl;}//display MM
    double pageFaultPercentage=(noOfPageFaults/noOfReads)*100;
    display(pageSize,noOfFrames,"FIFO",pageFaultPercentage);
}

void LRU(int pageSize, int noOfFrames)
{
    queue<int> PRQ = read(pageSize);

    //init
    int MM[noOfFrames];
    for(int i=0; i<noOfFrames; i++)MM[i]=-1;//NULL doesn't work for some reason, so -1 is "empty" because page 0 exists

    bool fault;//page fault?
    double noOfPageFaults=0;
    double noOfReads=PRQ.size();//number of virtual addresses in the file/program reads

    vector<int> PRV;//vector of the used page references
    vector<int>::iterator it;

    int targetValue=69;//page to replace
    //int targetIndex;//frame to replace

    bool found[noOfFrames];
    int noOfFound=0;

    bool loop;//i used this because i couldn't figure out how to exit a backwards iterator loop

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
        }


        else//if(fault==true)
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
                for(int j=0; j<noOfFrames; j++)found[j]=false;//reset found[]

                loop=true;
                for(it=PRV.end()-1;loop==true/*it<=PRV.begin()*/;it--)//from the current point to the back
                {
                    //cout<<*it;//testing purposes
                    for(int i=0; i<noOfFrames; i++)
                    {
                        if(*it==MM[i])//compare each frame to the current page reference
                        {
                            found[i]=true;

                            noOfFound=0;//reset

                            //if(found[]==true)targetValue=*it;
                            for(int j=0; j<noOfFrames; j++){if(found[j]==true)noOfFound++;}
                            if(noOfFound==noOfFrames){targetValue=*it/*or MM[i]*/;i=noOfFrames;/*it=PRV.begin()*/loop=false;}//exit both loops!
                        }
                    }
                }
                //end of algorithm

                /*
                cout<<"here's where the algorithm kicks in!"<<endl;
                cout<<"target page: "<<targetValue<<endl;
                cout<<"replace with: "<<PRQ.front()<<endl;
                */

                //replace target
                for(int i=0; i<noOfFrames; i++)
                {
                    if(MM[i]==targetValue)
                    {
                        //cout<<"target frame: "<<i<<endl;
                        MM[i]=PRQ.front();
                        PRV.push_back(PRQ.front());//3
                        PRQ.pop();
                    }
                }
            }
        }
        //for(int i=0; i<noOfFrames; i++){cout<<MM[i]<<endl;}cout<<endl;//display MM
    }
    double pageFaultPercentage=(noOfPageFaults/noOfReads)*100;
    display(pageSize,noOfFrames,"LRU ",pageFaultPercentage);
}

void MRU(int pageSize, int noOfFrames)
{
    queue<int> PRQ = read(pageSize);

    //init
    int MM[noOfFrames];
    for(int i=0; i<noOfFrames; i++)MM[i]=-1;//NULL doesn't work for some reason, so -1 is "empty" because page 0 exists

    bool fault;//page fault
    double noOfPageFaults=0;
    double noOfReads=PRQ.size();//number of virtual addresses in the file/program reads

    stack<int> PRS;//stack of the used page references

    int targetValue;
    //int targetIndex;

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
    double pageFaultPercentage=(noOfPageFaults/noOfReads)*100;
    display(pageSize,noOfFrames,"MRU ",pageFaultPercentage);
}

void OPT(int pageSize, int noOfFrames)
{
   //INITALIZE
    int MM[noOfFrames];//memory allocated to the process - ex. mem[frame][offset] contains the physical address
    for(int i=0; i < noOfFrames; i++)
    {
        MM[i]=-1;//NULL doesn't work for some reason, so -1 is "empty" because page 0 exists
    }
    queue<int> PRQ;//page reference queue
    vector<int> PRV;//vector of the used page references
    vector<int>::iterator it;
    vector<int> FRV;

    //read in virtual addresses
    fstream fin;
    fin.open("data.txt",ios::in);
    if (!fin){cout<<"Could not find file =(\n";}

    int temp;

    while(!fin.eof())
    {
        fin>>temp;
        fin.ignore(100,'\n');
        temp=temp/pageSize;
        FRV.push_back(temp);
        PRQ.push(temp);
        if(isdigit(fin.peek())==false)
        {
           fin.ignore(100,'\n');
        }//ignores the last line
    }
    fin.close();

    bool fault;//page fault?
    double noOfPageFaults=0;
    double noOfReads=PRQ.size();//number of virtual addresses in the file/program reads



    int targetValue=69;//page to replace
    //int targetIndex;//frame to replace

    bool found[noOfFrames];
    int noOfFound=0;

    bool loop;

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
        }


        else//if(fault==true)
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
                for(int j=0; j<noOfFrames; j++)found[j]=false;//reset found[]

                loop=true;
                for(it=FRV.end()-1;loop==true;it--)//from the current point to the front
                {
                    for(int i=0; i < noOfFrames; i++)
                    {
                        if(*it==MM[i])//compare each frame to the current page reference
                        {
                            found[i]=true;

                            noOfFound=0;//reset

                            for(int j=0; j<noOfFrames; j++)
                                {
                                    if(found[j]==true)noOfFound++;
                                }
                            if(noOfFound==noOfFrames)
                            {
                                targetValue=*it;
                                i=noOfFrames;
                                loop=false;
                            }//exit both loops!
                        }
                    }
                }
                //end of algorithm

                /*
                cout<<"here's where the algorithm kicks in!"<<endl;
                cout<<"target page: "<<targetValue<<endl;
                cout<<"replace with: "<<PRQ.front()<<endl;
                */

                //replace target
                for(int i=0; i<noOfFrames; i++)
                {
                    if(MM[i]==targetValue)
                    {
                        MM[i]=PRQ.front();
                        PRV.push_back(PRQ.front());//3
                        PRQ.pop();
                    }
                }
            }
        }
    }
    double pageFaultPercentage = (noOfPageFaults/noOfReads)*100;
    display(pageSize,noOfFrames,"OPT ",pageFaultPercentage);
}
