#ifndef STAFF_H
#define STAFF_H

#include <string>
#include <iostream>


class Staff {
public:
  Staff ();
  Staff (std::string surname, std::string givenName);

  std::string getSurname() const       {return theSurname;}
  void putSurname(std::string surname) {theSurname = surname;}

  std::string getGivenName() const         {return theGivenName;}
  void putGivenName(std::string givenName) {theGivenName = givenName;}


  bool operator== (const Staff& r) const;
  bool operator< (const Staff& r) const;

private:
  std::string theSurname;
  std::string theGivenName;
};


std::ostream& operator<< (std::ostream& out, const Staff& s);

#endif
