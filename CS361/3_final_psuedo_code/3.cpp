//CS361 Final
//using psuedocode


//information gathering system
//1)creates a vector of data
//2)puts the vector inside a priority queue sorted by rank
class data
{
    private:
        time_t timestamp;
        int rank;
        string info;
    public:
        data(time_t t, int r, string i)//set data
        get timestamp;
}
int main()
{
    time_t timestamp;
    time (&timestamp);
    int r;
    int i;

    vector<data> myvector;

    loop//load myvector
    {
        mydata = new data;
        mydata(timestamp, 3, "top secret stuff");
        myvector.pushfront(mydata);
    }

    priority_queue<int,vector<data>,greater<data> > lastrank>
}

//network of agents & message passing
//1) agent has a name + message
//2) can only receive if they know the codeword
//3) only higher ranks can get their message
class network
{
    private:
        int name;//007
        int rank;
        data message;//using class data
        string codeword="fish";
    public:
        receivemessage(data m, string c){if(c==codeword){m=message}}
        getmessage(int r){if(r>rank){return message;}
}
