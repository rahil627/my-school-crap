#include "shapefactory.h"

//#include "circle.h"	
#include "picture.h"	
#include "rectangle.h"
#include "point.h"
#include "polygon.h"
#include "line.h"
// #include "composite.h"

using namespace std;


/**
   The ShapeFactory class is designed to hide the actual list of available
   shapes so that new shapes may be added to the system by only altering
   the code in shapefactoy.cpp.

   The primary operation provided by this class is newShape. newShape 
   receives an indicator of the type of shape to be created (passed as
   a string) and returns a pointer to a newly created instance of the 
   shape subclass, created via the subclass's default constructor.
**/

Shape* ShapeFactory::prototypes[] = {
  // new Circle(),
  new Rectangle(),
  new Line(),
  //  new Composite(),
  new Polygon()
};


string ShapeFactory::knownShapes[] = {
  // string("Circle"),
  string("Rectangle"),
  string("Line"),
  //  string("Composite"),
  string("Polygon"),
  string() // marks the end of the list
};
  


// Create a new default object of the indicated subclass
Shape* ShapeFactory::newShape (std::string shapeSubclassName)
{
  for (int i = 0; knownShapes[i] != string(); ++i)
    {
      if (shapeSubclassName == knownShapes[i])
	return prototypes[i]->clone();
    }
  return NULL;
}


// Can this factory create an object of the indicated subclass?
bool ShapeFactory::canCreate (std::string shapeSubclassName)
{
  for (int i = 0; knownShapes[i] != string(); ++i)
    {
      if (shapeSubclassName == knownShapes[i])
	return true;
    }
  return false;
}
