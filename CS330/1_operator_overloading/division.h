#ifndef DIVISION_H
#define DIVISION_H

#include <iostream>
#include <string>
#include <list>

#include "project.h"


class Division {
public:
  Division();

  std::string getName() const {return theName;}
  void putName(std::string name) {theName = name;}

  std::string getRoom() const {return theRoom;}
  void putRoom(std::string room) {theRoom = room;}

  std::string getBuilding() const {return theBuilding;}
  void putBuilding(std::string building) {theBuilding = building;}

  std::string getDivisionCode() const {return theDivisionCode;}
  void putDivisionCode(std::string divisionCode) {theDivisionCode = divisionCode;}

  void addProject(Project);
  int numberOfProjects() const;

  typedef std::list<Project> ProjectSequence;
  typedef ProjectSequence::iterator iterator;
  typedef ProjectSequence::const_iterator const_iterator;

  iterator begin();
  iterator end();

  const_iterator begin() const;
  const_iterator end() const;

private:
  std::string theName;
  std::string theRoom;
  std::string theBuilding;
  std::string theDivisionCode;

  std::list<Project> activeProjects;
};


std::istream& operator>> (std::istream& in, Division& div);
std::ostream& operator<< (std::ostream& out, const Division& div);


#endif


  
