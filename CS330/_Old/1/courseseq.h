#ifndef CourseSeq_H
#define CourseSeq_H

#include "course.h"

class CourseSequence {
private:
  struct CourseSequenceNode;

public:

  typedef Course CourseType;
  typedef CourseType& CourseRef;
  typedef const CourseType& ConstCourseRef;

  // Constructors & assignment
  CourseSequence();
  CourseSequence(const CourseSequence&);
  ~CourseSequence();

  CourseSequence& operator= (const CourseSequence&);


  // General info
  unsigned size() const;

  bool operator== (const CourseSequence&) const;


  // Positions within the sequence
  typedef CourseSequenceNode* Position;
  typedef const CourseSequenceNode* ConstPosition;


  ConstCourseRef at(ConstPosition) const;
  CourseRef at(Position);

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
  ConstPosition find(ConstCourseRef) const;
  Position find(ConstCourseRef);

  // search for first element that is >= input value - return NULL if not fount
  ConstPosition findFirstGreaterEqual(ConstCourseRef) const;
  Position findFirstGreaterEqual(ConstCourseRef);


  // Adding and removing elements
  void addToFront (ConstCourseRef);
  void addToBack (ConstCourseRef);

  void addAfter (Position, ConstCourseRef);
  void addBefore (Position, ConstCourseRef);

  void removeFront();
  void removeBack();
  void remove (Position);

  void clear();


private:

  struct CourseSequenceNode {
    CourseType data;
    CourseSequenceNode* prev;
    CourseSequenceNode* next;

    CourseSequenceNode (ConstCourseRef value,
			 CourseSequenceNode* prevNode,
			 CourseSequenceNode* nextNode)
      : data(value), prev(prevNode), next(nextNode)
      {}
  };

  unsigned theSize;
  CourseSequenceNode* theFront;
  CourseSequenceNode* theBack;
};

#endif
