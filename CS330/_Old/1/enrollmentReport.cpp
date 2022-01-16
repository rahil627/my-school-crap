#include <algorithm>
#include <iostream>
#include <fstream>
#include <utility>

#include "section.h"
#include "sectionseq.h"
#include "course.h"
#include "courseseq.h"
#include "split.h"

//
// This program reads a set of course information from sections.dat
// and a set of enrollment information from enrollment.dat, then produces
// a set of reports on Under graduate, Graduate, and total enrollment.
//

// #define DEBUG
#include "debug.h"


using namespace std;




void readSections (SectionSequence& sections)
{
  ifstream enrollIn ("enrollment.dat");
  string callNumber;
  while (enrollIn >> callNumber) {
    Student stu;
    enrollIn >> stu;

    Section sect ("", callNumber, "");
    SectionSequence::Position pos = sections.find(sect);
    if (pos == NULL) {
      sections.addToBack(sect);
      pos = sections.back();
    }
    Section& section = sections.at(pos);
    section.addStudent (stu);
  }
}



void readCourses (CourseSequence& courses, SectionSequence& sections)
{
  using namespace std::rel_ops;

  ifstream sectionsIn ("sections.dat");
  while (sectionsIn) {
    string line;
    getline(sectionsIn, line);

    string fields[3];
    int fieldCount = split(line, "\t", fields, 3);
    if (fieldCount != 3)
      break;

    Section sect (fields[1] /* title*/,
		  fields[0] /* call # */,
		  fields[2] /* instructor */);

    SectionSequence::Position pos = sections.find(sect);
    if (pos == NULL)
      {
	sections.addToBack (sect);
	pos = sections.back();
      }
    else
      {
	Section& section = sections.at(pos);
	section.putTitle (fields[1]);
	section.putInstructor (fields[2]);
      }

    string courseName = fields[1];
    string::size_type numericStart = courseName.find_first_of("0123456789");
    string dept = courseName.substr(0,numericStart);
    string number = courseName.substr(numericStart);

    Course c (dept, number);
    CourseSequence::Position cpos = courses.findFirstGreaterEqual(c);
    if (cpos == NULL) {
      courses.addToBack(c);
      cpos = courses.back();
    } else if (courses.at(cpos) != c) {
      courses.addBefore (cpos, c);
      cpos = courses.getPrevious(cpos);
    }
    Course& c2 = courses.at(cpos);
    c2.addSection(sections.at(pos));
  }
}


bool sectionOrder (const Section& left, const Section& right)
{
  // return true if, when printing our report, we want
  // left to appear in the report before right
  return (left.getCallNumber() < right.getCallNumber());
}


string padString (string::size_type length, string s)
{
  // Clip s to the desired length or pad it with blanks on the right
  // to get it up to that length.
  if (s.length() <= length)
    return s + string(length - s.length(), ' ');
  else
    return s.substr(0, length);
}



void printReport (CourseSequence& courseSet)
{
  // List each course (in the order they occurred
  //   in the courses.dat file)
  for(CourseSequence::Position pos = courseSet.front();
      pos != 0; pos = courseSet.getNext(pos))
    {
      Course& course = courseSet.at(pos);
      cout << course.getDept() << " " << course.getNumber() << endl;

      // For each course,
      if (course.numberOfSections() > 0)
	{
	  // sort the sections produced by that course
	  Section* sections = new Section[course.numberOfSections()];
	  int k = 0;
	  for (Course::Position p = course.front();
	       p != 0; p = course.getNext(p))
	    {
	      sections[k] = course.at(p);
	      ++k;
	    }
	  sort (sections, sections+course.numberOfSections(), sectionOrder);

	  // and print them
	  for (int sect = 0; sect < course.numberOfSections(); ++sect)
	    {
	      Section b = sections[sect];
	      if (b.numberOfStudents() > 0) {
		cout << b;
		cout << endl;
	      }
	    }
	  delete [] sections;
	}
    }
}


void printSummaryReport (CourseSequence& courseSet)
{
  // List each course (in the order they occurred
  //   in the courses.dat file)
  for(CourseSequence::Position pos = courseSet.front();
      pos != 0; pos = courseSet.getNext(pos))
    {
      Course& course = courseSet.at(pos);
      cout << course.getDept() << " " << course.getNumber() << endl;

      // For each course,
      if (course.numberOfSections() > 0)
	{
	  // sort the sections produced by that course
	  Section* sections = new Section[course.numberOfSections()];
	  int k = 0;
	  for (Course::Position p = course.front();
	       p != 0; p = course.getNext(p))
	    {
	      sections[k] = course.at(p);
	      ++k;
	    }
	  sort (sections, sections+course.numberOfSections(), sectionOrder);

	  // and print them
	  for (int sect = 0; sect < course.numberOfSections(); ++sect)
	    {
	      Section b = sections[sect];
	      cout << string(4, ' ');
	      cout << b.getCallNumber() << ' ';
	      cout << padString(38, b.getInstructor()) << ' ';

	      cout << "   enrollment: " << b.numberOfStudents();
	      cout << endl;
	    }
	  delete [] sections;
	}
    }
}


void doReports(CourseSequence& courseSet)
{
  CourseSequence ugradCourses;
  CourseSequence gradCourses;

  for(CourseSequence::Position pos = courseSet.front();
      pos != 0; pos = courseSet.getNext(pos))
    {
      Course& course = courseSet.at(pos);
      if (course.getNumber() < "500")
	ugradCourses.addToFront(course);
      else
	gradCourses.addToFront(course);
    }
  cout << "Undergraduate enrollments\n";
  printSummaryReport (ugradCourses);
  cout << "\n\n\nGraduate Enrollments\n";
  printSummaryReport (gradCourses);
}


int main(int argc, char** argv)
{
  CourseSequence courseSet;
  SectionSequence sectionSet;
  readSections (sectionSet);
  readCourses (courseSet, sectionSet);

  doReports(courseSet);
  printReport (courseSet);

  return 0;
}
