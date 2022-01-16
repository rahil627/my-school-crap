#ifndef COURSE_H
#define COURSE_H

#include <iostream>
#include <string>
#include "section.h"
#include "sectionseq.h"


class Course {
public:
  Course();

  Course(std::string dept, std::string number);

  std::string getDept() const {return theDept;}
  void putDept(std::string name) {theDept = name;}

  std::string getNumber() const {return theNumber;}
  void putNumber(std::string room) {theNumber = room;}

  std::string getTitle() const {return theTitle;}
  void putTitle(std::string building) {theTitle = building;}

  std::string getDescription() const {return theDescription;}
  void putDescription(std::string courseCode) {theDescription = courseCode;}

  void addSection(Section);
  int numberOfSections() const;

  typedef SectionSequence::Position Position;
  typedef SectionSequence::ConstPosition ConstPosition;

  const Section at(ConstPosition) const;
  Section at(Position);

  Position front();
  ConstPosition front() const;

  Position back();
  ConstPosition back() const;

  ConstPosition getNext (ConstPosition) const;
  Position getNext (Position);

  ConstPosition getPrevious (ConstPosition) const;
  Position getPrevious (Position);


private:
  std::string theDept;
  std::string theNumber;
  std::string theTitle;
  std::string theDescription;

  SectionSequence sections;
};


std::istream& operator>> (std::istream& in, Course& c);
std::ostream& operator<< (std::ostream& out, const Course& c);

bool operator== (const Course& left, const Course& right);
bool operator< (const Course& left, const Course& right);

#endif


  
