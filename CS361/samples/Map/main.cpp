   #include <iostream>
   #include <string>
   #include <map>


int main()
{
    map<string,int> count;

    string word;

    while (cin >> word) count[word]++;

    cout << count << endl;

    return 0;
}
