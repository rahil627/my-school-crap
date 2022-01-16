#include "student.h"

using namespace std;

Student::Student ()
{}


Student::Student (std::string surname, std::string givenDept)
  : theSurname (surname), theGivenName(givenDept)
{}


istream& operator>> (istream& in, Student& s)
{
  string lastName, firstName;
  in >> lastName >> firstName;
  if (lastName[lastName.length() - 1] == ',')
    lastName.erase(lastName.length() - 1, 1);
  s.putSurname (lastName);
  s.putGivenName (firstName);
  return in;
}
