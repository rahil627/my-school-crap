#ifndef SECTION_H
#define SECTION_H

#include <iostream>
#include <string>
#include "student.h"

class Course;


class Section {
public:
  Section();
  ~Section();
  Section (std::string title, std::string callNumber, std::string instructor);
  std::string getTitle() const     {return theTitle;}
  void putTitle(std::string title) {theTitle = title;}

  std::string getCallNumber() const     {return theCallNumber;}
  void putCallNumber(std::string sectionID)  {theCallNumber = sectionID;}

  std::string getInstructor() const    {return theInstructor;}
  void putInstructor(std::string sectionID) {theInstructor = sectionID;}

  void addStudent(const Student&);
  int numberOfStudents() const;
  Student getStudent(int studentNumber) const;


private:
  std::string theTitle;
  std::string theCallNumber;
  std::string theInstructor;

  int numStudent;

  // linked list of students
  struct StudentNode {
    Student data;
    StudentNode* next;

    StudentNode (const Student& stu);
  };

  StudentNode* firstStudent;
  StudentNode* lastStudent;


  friend std::ostream& operator<< (std::ostream& out, const Section& s);

};

std::ostream& operator<< (std::ostream& out, const Section& s);


#endif
