#include "division.h"
#include "project.h"
#include "split.h"

using namespace std;


Division::Division() {}


void Division::addProject(Project b)
{
  activeProjects.push_back (b);
}


int Division::numberOfProjects() const
{
  return activeProjects.size();
}


  
istream& operator>> (istream& in, Division& p)
{
  string line;
  if (in)
    {
      getline(in, line);
    }

  string fields[4];
  int fieldCount = split(line, "\t", fields, 4);

  p = Division();
  p.putName (fields[0]);
  p.putRoom (fields[1]);
  p.putBuilding (fields[2]);
  p.putDivisionCode (fields[3]);
}
      

std::ostream& operator<< (std::ostream& out, const Division& div)
{
  out << div.getName() << "\t"
      << div.getDivisionCode() << "\t"
      << div.getBuilding() << ":" << div.getRoom()
      << "\n";
  if (div.numberOfProjects() > 0)
    {
      int i = 0;
      for (Division::const_iterator p = div.begin();
	   p != div.end(); ++p)
	{
	  ++i;
	  out << i << "/" << div.numberOfProjects() << ": ";
	  out << *p << "\n";
	}
    }
  else
    out << "(no projects)\n";
  return out;
}






Division::iterator Division::begin()
{
  return activeProjects.begin();
}

Division::const_iterator Division::begin() const
{
  return activeProjects.begin();
}

Division::iterator Division::end()
{
  return activeProjects.end();
}

Division::const_iterator Division::end() const
{
  return activeProjects.end();
}



