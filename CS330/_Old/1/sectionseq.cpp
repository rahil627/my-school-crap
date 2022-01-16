#include "sectionseq.h"
#include <cassert>

using namespace std;

  // Constructors & assignment
SectionSequence::SectionSequence()
  : theSize(0), theFront(0), theBack(0)
{}

SectionSequence::SectionSequence(const SectionSequence& seq)
  : theSize(0), theFront(0), theBack(0)
{
  for (Position p = seq.theFront; p != 0; p = p->next)
    addToBack(p->data);
}

SectionSequence::~SectionSequence()
{
  clear();
}

SectionSequence& SectionSequence::operator= (const SectionSequence& seq)
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
unsigned SectionSequence::size() const
{
  return theSize;
}


bool SectionSequence::operator== (const SectionSequence& seq) const
{
  if (theSize != seq.theSize)
    return false;

  ConstPosition p1 = theFront;
  ConstPosition p2 = seq.theFront;
  while (p1 != 0)
    {
      if (!(p1->data == p2->data))
	return false;
      p1 = p1->next;
      p2 = p2->next;
    }
  return true;
}


SectionSequence::ConstSectionRef
SectionSequence::at(SectionSequence::ConstPosition pos) const
{
  assert (pos != 0);
  return pos->data;
}


SectionSequence::SectionRef
SectionSequence::at(SectionSequence::Position pos)
{
  assert (pos != 0);
  return pos->data;
}


SectionSequence::Position SectionSequence::front()
{
  return theFront;
}

SectionSequence::ConstPosition SectionSequence::front() const
{
  return theFront;
}

SectionSequence::Position SectionSequence::back()
{
  return theBack;
}

SectionSequence::ConstPosition SectionSequence::back() const
{
  return theBack;
}


SectionSequence::ConstPosition
SectionSequence::getNext (SectionSequence::ConstPosition pos) const
{
  assert (pos != 0);
  return pos->next;
}

SectionSequence::Position
SectionSequence::getNext (SectionSequence::Position pos)
{
  assert (pos != 0);
  return pos->next;
}


SectionSequence::ConstPosition
SectionSequence::getPrevious (SectionSequence::ConstPosition pos) const
{
  assert (pos != 0);
  return pos->prev;
}



SectionSequence::Position
SectionSequence::getPrevious (SectionSequence::Position pos)
{
  assert (pos != 0);
  return pos->prev;
}



// Searching
SectionSequence::ConstPosition
SectionSequence::find(SectionSequence::ConstSectionRef key) const
{
  ConstPosition p = theFront;
  while (p != 0 && !(p->data == key))
    p = p->next;
  return p;
}

SectionSequence::Position
SectionSequence::find(SectionSequence::ConstSectionRef key)
{
  Position p = theFront;
  while (p != 0 && !(p->data == key))
    p = p->next;
  return p;
}


// search for first element that is >= input value - return NULL if not found
SectionSequence::ConstPosition 
  SectionSequence::findFirstGreaterEqual
    (SectionSequence::ConstSectionRef key) const
{
  ConstPosition p = theFront;
  while (p != 0 && (key < p->data))
    p = p->next;
  return p;
}


SectionSequence::Position
  SectionSequence::findFirstGreaterEqual(SectionSequence::ConstSectionRef key)
{
  Position p = theFront;
  while (p != 0 && (key < p->data))
    p = p->next;
  return p;
}


// Adding and removing elements
void SectionSequence::addToFront (SectionSequence::ConstSectionRef value)
{
  if (theFront == 0)
    {
      theFront = theBack = new SectionSequenceNode (value, 0, 0);
    }
  else
    {
      Position oldFirst = theFront;
      theFront = oldFirst->prev = new SectionSequenceNode (value, 0, oldFirst);
    }
  ++theSize;
}

void SectionSequence::addToBack (SectionSequence::ConstSectionRef value)
{
  if (theBack == 0)
    {
      theFront = theBack = new SectionSequenceNode (value, 0, 0);
    }
  else
    {
      Position oldLast = theBack;
      theBack = oldLast->next = new SectionSequenceNode (value, oldLast, 0);
    }
  ++theSize;
}


void SectionSequence::addAfter (SectionSequence::Position pos,
				SectionSequence::ConstSectionRef value)
{
  assert (pos != 0);
  Position newNode = new SectionSequenceNode(value, pos, pos->next);
  if (pos->next != 0)
    pos->next->prev = newNode;
  pos->next = newNode;
  if (theBack == pos)
    theBack = newNode;
  ++theSize;
}

void SectionSequence::addBefore (SectionSequence::Position pos,
				 SectionSequence::ConstSectionRef value)
{
  assert (pos != 0);
  Position newNode = new SectionSequenceNode(value, pos->prev, pos);
  if (pos->prev != 0)
    pos->prev->next = newNode;
  pos->prev = newNode;
  if (theFront == pos)
    theFront = newNode;
  ++theSize;
}
  

void SectionSequence::removeFront()
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


void SectionSequence::removeBack()
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

void SectionSequence::remove (SectionSequence::Position pos)
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

void SectionSequence::clear()
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


