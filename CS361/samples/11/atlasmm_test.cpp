#include <iostream>
#include <string>
#include <map>
using namespace std;

#include "atlasmm.h"

int main()
{
	atlasmm pubmap;

	int count =0;
cout<<"creating the default mmap"<<endl;
	inside * tempi;
	tempi = new inside;
	tempi->seti(1);
	pubmap.addm("bob", *tempi);

	tempi = new inside;
	tempi->seti(2);
	pubmap.addm("fred", *tempi);

	tempi = new inside;
	tempi->seti(3);
	pubmap.addm("joan", *tempi);

	count = pubmap.getsize();
	cout<<"there are "<<count<<" objects in the atlas"<<endl;
	pubmap.showmap();

system("pause");
    cout<<"expanding the mmap"<<endl;
	for(int j=0; j<5; j++)
	{
	tempi = new inside;
	tempi->seti(j+3);
	pubmap.addm("joan", *tempi );
	}

	count = pubmap.getsize();
	cout<<"there are "<<count<<" objects in the atlas"<<endl;
	pubmap.showmap();
system("pause");

cout<<"deletion of poor fred"<<endl;
	pubmap.delm("fred");
	count = pubmap.getsize();
	cout<<"there are "<<count<<" objects in the atlas"<<endl;
	pubmap.showmap();
system("pause");

cout<<"adding bob-4"<<endl;
	tempi = new inside;
	tempi->seti(4);
	pubmap.addm("bob", *tempi );
	count = pubmap.getsize();
	cout<<"there are "<<count<<" objects in the atlas"<<endl;
	pubmap.showmap();
system("pause");

cout<<"deleting bob"<<endl;
	pubmap.delm("bob");
	count = pubmap.getsize();
	cout<<"there are "<<count<<" objects in the atlas"<<endl;
	pubmap.showmap();
system("pause");

cout<<"joan-3's turn!"<<endl;
	pubmap.dels("joan", 3);
	count = pubmap.getsize();
	cout<<"there are "<<count<<" objects in the atlas"<<endl;
	pubmap.showmap();

cout<<"deleting joan"<<endl;
	pubmap.delm("joan");
	count = pubmap.getsize();
	cout<<"there are "<<count<<" objects in the atlas"<<endl;
	pubmap.showmap();
system("pause");

return 0;
}
