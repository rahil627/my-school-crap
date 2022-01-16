#ifndef SHAPEFACTORY_H
#define SHAPEFACTORY_H


#include <string>


class Shape;

/**
   The ShapeFactory class is designed to hide the actual list of available
   shapes so that new shapes may be added to the system by only altering
   the code in shapefactoy.cpp.

   The primary operation provided by this class is newShape. newShape 
   receives an indicator of the type of shape to be created (passed as
   a string) and returns a pointer to a newly created instance of the 
   shape subclass, created via the subclass's default constructor.
**/

class ShapeFactory
{
public:
  // Create a new default object of the indicated subclass
  static Shape* newShape (std::string shapeSubclassName);

  // Can this factory create an object of the indicated subclass?
  static bool canCreate (std::string shapeSubclassName);

private:
static Shape* prototypes[];
static std::string ShapeFactory::knownShapes[];

};


#endif
