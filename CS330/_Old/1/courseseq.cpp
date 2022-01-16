#include "courseseq.h"
#include <cassert>
#include <utility>

using namespace std;
using namespace std::rel_ops;

  // Constructors & assignment
CourseSequence::CourseSequence()
  : theSize(0), theFront(0), theBack(0)
{}

CourseSequence::CourseSequence(const CourseSequence& seq)
  : theSize(0), theFront(0), theBack(0)
{
  for (Position p = seq.theFront; p != 0; p = p->next)
    addToBack(p->data);
}

CourseSequence::~CourseSequence()
{
  clear();
}

CourseSequence& CourseSequence::operator= (const CourseSequence& seq)
{
  if (theFront != seq.theFront)
    {
      clear();
      for (Position p = seq.theFront; p != 0; p = p->next)
	addToBack(p->data);
    }
  return *this;
}



// General info
unsigned CourseSequence::size() const
{
  return theSize;
}


bool CourseSequence::operator== (const CourseSequence& seq) const
{
  if (theSize != seq.theSize)
    return false;

  ConstPosition p1 = theFront;
  ConstPosition p2 = seq.theFront;
  while (p1 != 0)
    {
      if (p1->data != p2->data)
	return false;
      p1 = p1->next;
      p2 = p2->next;
    }
  return true;
}


CourseSequence::ConstCourseRef
CourseSequence::at(CourseSequence::ConstPosition pos) const
{
  assert (pos != 0);
  return pos->data;
}


CourseSequence::CourseRef
CourseSequence::at(CourseSequence::Position pos)
{
  assert (pos != 0);
  return pos->data;
}


CourseSequence::Position CourseSequence::front()
{
  return theFront;
}

CourseSequence::ConstPosition CourseSequence::front() const
{
  return theFront;
}

CourseSequence::Position CourseSequence::back()
{
  return theBack;
}

CourseSequence::ConstPosition CourseSequence::back() const
{
  return theBack;
}


CourseSequence::ConstPosition
CourseSequence::getNext (CourseSequence::ConstPosition pos) const
{
  assert (pos != 0);
  return pos->next;
}

CourseSequence::Position
CourseSequence::getNext (CourseSequence::Position pos)
{
  assert (pos != 0);
  return pos->next;
}


CourseSequence::ConstPosition
CourseSequence::getPrevious (CourseSequence::ConstPosition pos) const
{
  assert (pos != 0);
  return pos->prev;
}



CourseSequence::Position
CourseSequence::getPrevious (CourseSequence::Position pos)
{
  assert (pos != 0);
  return pos->prev;
}



// Searching
CourseSequence::ConstPosition
CourseSequence::find(CourseSequence::ConstCourseRef key) const
{
  ConstPosition p = theFront;
  while (p != 0 && !(p->data == key))
    p = p->next;
  return p;
}

CourseSequence::Position
CourseSequence::find(CourseSequence::ConstCourseRef key)
{
  Position p = theFront;
  while (p != 0 && !(p->data == key))
    p = p->next;
  return p;
}


// search for first element that is >= input value - return NULL if not found
CourseSequence::ConstPosition 
  CourseSequence::findFirstGreaterEqual
    (CourseSequence::ConstCourseRef key) const
{
  ConstPosition p = theFront;
  while (p != 0 && (key < p->data))
    p = p->next;
  return p;
}


CourseSequence::Position
  CourseSequence::findFirstGreaterEqual(CourseSequence::ConstCourseRef key)
{
  Position p = theFront;
  while (p != 0 && (key < p->data))
    p = p->next;
  return p;
}



// Adding and removing elements
void CourseSequence::addToFront (CourseSequence::ConstCourseRef value)
{
  if (theFront == 0)
    {
      theFront = theBack = new CourseSequenceNode (value, 0, 0);
    }
  else
    {
      Position oldFirst = theFront;
      theFront = oldFirst->prev = new CourseSequenceNode (value, 0, oldFirst);
    }
  ++theSize;
}

void CourseSequence::addToBack (CourseSequence::ConstCourseRef value)
{
  if (theBack == 0)
    {
      theFront = theBack = new CourseSequenceNode (value, 0, 0);
    }
  else
    {
      Position oldLast = theBack;
      theBack = oldLast->next = new CourseSequenceNode (value, oldLast, 0);
    }
  ++theSize;
}


void CourseSequence::addAfter (CourseSequence::Position pos,
				CourseSequence::ConstCourseRef value)
{
  assert (pos != 0);
  Position newNode = new CourseSequenceNode(value, pos, pos->next);
  if (pos->next != 0)
    pos->next->prev = newNode;
  pos->next = newNode;
  if (theBack == pos)
    theBack = newNode;
  ++theSize;
}

void CourseSequence::addBefore (CourseSequence::Position pos,
				 CourseSequence::ConstCourseRef value)
{
  assert (pos != 0);
  Position newNode = new CourseSequenceNode(value, pos->prev, pos);
  if (pos->prev != 0)
    pos->prev->next = newNode;
  pos->prev = newNode;
  if (theFront == pos)
    theFront = newNode;
  ++theSize;
}
  

void CourseSequence::removeFront()
{
  assert (theSize > 0);
  Position oldFront = theFront;
  theFront = theFront->next;
  if (theFront != 0)
    theFront->prev = 0;
  else
    theBack = 0;
  delete oldFront;
  --theSize;
}


void CourseSequence::removeBack()
{
  assert (theSize > 0);
  Position oldLast = theBack;
  theBack = theBack->prev;
  if (theBack != 0)
    theBack->next = 0;
  else
    theFront = 0;
  delete oldLast;
  --theSize;
}

void CourseSequence::remove (CourseSequence::Position pos)
{
  assert (pos != 0);
  if (pos == theFront)
    removeFront();
  else if (pos == theBack)
    removeBack();
  else
    {
      pos->next->prev = pos->prev;
      pos->prev->next = pos->next;
      delete pos;
      --theSize;
    }
}

void CourseSequence::clear()
{
  Position p = theFront;
  while (p != 0)
    {
      Position nxt = p->next;
      delete p;
      p = nxt;
    }
  theFront = theBack = 0;
  theSize = 0;
}


