#include "staff.h"

using namespace std;

Staff::Staff ()
{}


Staff::Staff (std::string surname, std::string givenName)
  : theSurname (surname), theGivenName(givenName)
{}

std::ostream& operator<< (std::ostream& out, const Staff& s)
{
  out << s.getSurname();
  if (s.getGivenName().length() > 0)
    out << ", " << s.getGivenName();
  return out;
}

bool Staff::operator== (const Staff& r) const
{
  return theSurname == r.theSurname
    && theGivenName == r.theGivenName;
}

bool Staff::operator< (const Staff& r) const
{
  if (theSurname < r.theSurname)
    return true;
  if (theSurname > r.theSurname)
    return false;
  return (theGivenName < r.theGivenName);
}
