#ifndef SECTIONSEQ_H
#define SECTIONSEQ_H

#include "section.h"

class SectionSequence {
private:
  struct SectionSequenceNode;

public:

  typedef Section SectionType;
  typedef SectionType& SectionRef;
  typedef const SectionType& ConstSectionRef;

  // Constructors & assignment
  SectionSequence();
  SectionSequence(const SectionSequence&);
  ~SectionSequence();

  SectionSequence& operator= (const SectionSequence&);


  // General info
  unsigned size() const;

  bool operator== (const SectionSequence&) const;


  // Positions within the sequence
  typedef SectionSequenceNode* Position;
  typedef const SectionSequenceNode* ConstPosition;


  ConstSectionRef at(ConstPosition) const;
  SectionRef at(Position);

  Position front();
  ConstPosition front() const;

  Position back();
  ConstPosition back() const;

  ConstPosition getNext (ConstPosition) const;
  Position getNext (Position);

  ConstPosition getPrevious (ConstPosition) const;
  Position getPrevious (Position);


  // Searching

  // search for an == element - return NULL if not found
  ConstPosition find(ConstSectionRef) const;
  Position find(ConstSectionRef);

  // search for first element that is >= input value - return NULL if not fount
  ConstPosition findFirstGreaterEqual(ConstSectionRef) const;
  Position findFirstGreaterEqual(ConstSectionRef);


  // Adding and removing elements
  void addToFront (ConstSectionRef);
  void addToBack (ConstSectionRef);

  void addAfter (Position, ConstSectionRef);
  void addBefore (Position, ConstSectionRef);

  void removeFront();
  void removeBack();
  void remove (Position);

  void clear();


private:

  struct SectionSequenceNode {
    SectionType data;
    SectionSequenceNode* prev;
    SectionSequenceNode* next;

    SectionSequenceNode (ConstSectionRef value,
			 SectionSequenceNode* prevNode,
			 SectionSequenceNode* nextNode)
      : data(value), prev(prevNode), next(nextNode)
      {}
  };

  unsigned theSize;
  SectionSequenceNode* theFront;
  SectionSequenceNode* theBack;
};

#endif
