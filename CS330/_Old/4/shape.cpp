#include "shape.h"
#include "shapefactory.h"
#include <cassert>

using namespace std;

std::istream& operator>> (std::istream& in, Shape*& sh)
{
  std::string shapeKind;
  if (in >> shapeKind) {
    sh = ShapeFactory::newShape(shapeKind);
    if (sh != NULL)
      sh->get(in);
    else
      {
	cerr << "Warning: could not create shape " << shapeKind << endl;
	getline(in, shapeKind);
      }
  }
  return in;
}

