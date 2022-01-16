#include "course.h"
#include "section.h"
#include "split.h"

using namespace std;


Course::Course() {}


Course::Course(std::string dept, std::string number)
  : theDept(dept), theNumber(number)
{}


void Course::addSection(Section b)
{
  sections.addToBack (b);
}


int Course::numberOfSections() const
{
  return sections.size();
}


  
istream& operator>> (istream& in, Course& p)
{
  string line;
  if (in)
    {
      getline(in, line);
    }

  string fields[4];
  int fieldCount = split(line, "\t", fields, 4);

  p = Course();
  p.putDept (fields[0]);
  p.putNumber (fields[1]);
  p.putTitle (fields[2]);
  p.putDescription (fields[3]);
}
      

std::ostream& operator<< (std::ostream& out, const Course& c)
{
  out << c.getDept() << "\t"
      << c.getDescription() << "\t"
      << c.getTitle() << ":" << c.getNumber()
      << "\n";
  if (c.numberOfSections() > 0)
    {
      int i = 0;
      for (Course::ConstPosition p = c.front();
	   p != 0; p = c.getNext(p))
	{
	  ++i;
	  out << i << "/" << c.numberOfSections() << ": ";
	  out << c.at(p) << "\n";
	}
    }
  else
    out << "(no sections)\n";
  return out;
}




const Section Course::at(ConstPosition p) const
{
  return sections.at(p);
}

Section Course::at(Position p)
{
  return sections.at(p);
}


Course::Position Course::front()
{
  return sections.front();
}

Course::ConstPosition Course::front() const
{
  return sections.front();
}

Course::Position Course::back()
{
  return sections.back();
}

Course::ConstPosition Course::back() const
{
  return sections.back();
}


Course::ConstPosition Course::getNext (Course::ConstPosition p) const
{
  return sections.getNext(p);
}

Course::Position Course::getNext (Course::Position p)
{
  return sections.getNext(p);
}

Course::ConstPosition Course::getPrevious (Course::ConstPosition p) const
{
  return sections.getPrevious(p);
}

Course::Position Course::getPrevious (Course::Position p)
{
  return sections.getPrevious(p);
}


bool operator== (const Course& left, const Course& right)
{
  return left.getDept() == right.getDept()
    && left.getNumber() == right.getNumber();
}


bool operator< (const Course& left, const Course& right)
{
  return left.getDept() < right.getDept()
    || (left.getDept() == right.getDept()
	&& left.getNumber() < right.getNumber());
}

