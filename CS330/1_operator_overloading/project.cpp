#include "division.h"
#include "project.h"

#include <algorithm>
#include <cassert>
#include <cstdlib>

using namespace std;

Project::Project(int maxStaff)
  : theProjectID(""), theBudgetCode(""), theDivision(0), numStaff(0),
    theMaxStaff(maxStaff)
{
  staff = new Staff[maxStaff];
}


Project::iterator Project::begin() const
{
  return staff;
}


Project::iterator Project::end() const
{
  return staff + numStaff;
}




/**** insert your operations for Projects here  ***/

/* If you compile the code, you quickly discover the names of the missing
   functions: addStaff, numberOfStaff, and operator< 

   Part 1 of the assignment is to supply those, and none of
   them are particularly complicated */


void Project::addStaff(const Staff& au)
{
  assert (numStaff < theMaxStaff);
  int k = numStaff;
  while (k > 0 && au < staff[k-1])
    {
      staff[k] = staff[k-1];
      k--;
    }
  staff[k] = au;
  ++numStaff;
}

int Project::numberOfStaff() const
{
  return numStaff;
}



/* Once those functions are in place, the program may produce the correct
   report but the leakTracer will report possible memory leaks.

   Some people will immediately note that the addStaff function
   allocates Staff objects on the heap and that there are no delete
   statements anywhere to clean up those objects.

   From this, it's not hard to deduce the need for a destructor.

   And if you know you need a destructor, the rule of the Big 3 says
   that you also need a copy constructor and an assignment operator.

   Alternatively, you might simply have realized that the Project data
   structure contains pointers to unique Staff objects. Pointers to
   objects not shared with others is the major indicator that shallow
   copying will not work, suggesting that we need a copy constructor
   and an assignment operator. Again, if you realized this, then the
   rule of the Big 3 should tell you that you need a destructor as
   well.

*/


Project::~Project()
{
  delete [] staff;
}

Project::Project (const Project& b)
  : theTitle(b.theTitle), theProjectID(b.theProjectID),
    theBudgetCode(b.theBudgetCode),
    theDivision(b.theDivision), numStaff(b.numStaff),
    theMaxStaff(b.theMaxStaff)
{
  staff = new Staff[theMaxStaff];
  for (int i = 0; i < numStaff; ++i)
    staff[i] = b.staff[i];
}

Project& Project::operator= (const Project& b)
{ 
  if (this != &b)
    {
      delete [] staff;
      staff = new Staff[b.theMaxStaff];

      theTitle = b.theTitle;
      theProjectID = b.theProjectID;
      theBudgetCode = b.theBudgetCode;
      theDivision = b.theDivision;
      numStaff = b.numStaff;

      for (int i = 0; i < numStaff; ++i)
	staff[i] = b.staff[i];
    }
  return *this;
}


bool Project::operator== (const Project& right) const
{
  return theProjectID == right.theProjectID;
}

bool Project::operator< (const Project& right) const
{
  return theProjectID < right.theProjectID;
}


  
std::ostream& operator<< (std::ostream& out, const Project& p)
{
  out << p.getTitle() << "\t"
      << p.getProjectID() << ":"
      << p.getBudgetCode() << ":";
  Division *div = p.getDivision();
  if (div != 0)
    out << p.getDivision()->getDivisionCode();
  out << "\t(";
  for (Project::iterator i = p.begin(); i != p.end(); ++i)
    {
      if (i != p.begin())
	out << " : ";
      out << *i;
    }
  out << ")";

  return out;
}

