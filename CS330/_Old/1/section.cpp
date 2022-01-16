#include "course.h"
#include "section.h"
#include "split.h"

#include <algorithm>
#include <cassert>
#include <cstdlib>

using namespace std;


Section::Section (std::string title, std::string callNumber, std::string instructor)
  : theTitle(title),
    theCallNumber(callNumber),
    theInstructor(instructor),
    numStudent(0),
    firstStudent(0),
    lastStudent(0)
{
}


Section::StudentNode::StudentNode (const Student& stu)
  : data(stu), next(0)
{
}


/**** insert your operations for Sections here  ***/

