#ifndef STUDENT_H
#define STUDENT_H

#include <string>
#include <iostream>


class Student {
public:
  Student ();
  Student (std::string surname, std::string givenDept);

  std::string getSurname() const       {return theSurname;}
  void putSurname(std::string surname) {theSurname = surname;}

  std::string getGivenName() const         {return theGivenName;}
  void putGivenName(std::string givenDept) {theGivenName = givenDept;}

private:
  std::string theSurname;
  std::string theGivenName;
};


inline
std::ostream& operator<< (std::ostream& out, const Student& s)
{
  out << s.getSurname();
  if (s.getGivenName().length() > 0)
    out << ", " << s.getGivenName();
  return out;
}

std::istream& operator>> (std::istream& in, Student& s);

#endif
