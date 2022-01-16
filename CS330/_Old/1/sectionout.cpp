#include "course.h"
#include "section.h"
#include "split.h"

#include <algorithm>
#include <cassert>
#include <cstdlib>

using namespace std;


  
std::ostream& operator<< (std::ostream& out, const Section& s)
{
  out << s.getCallNumber() << ":"
      << s.getInstructor() << ":";
  out << "\t" << s.numberOfStudents() << " students\n";
  for (Section::StudentNode* p = s.firstStudent; p != 0; p = p->next)
    {
      out << "\t" << p->data << "\n";
    }
  out << flush;

  return out;
}
