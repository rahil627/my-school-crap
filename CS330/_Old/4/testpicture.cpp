#include <fstream>
#include <cmath>

#include "picture.h"	
#include "point.h"
#include "shape.h"

/*-------------------------------------------------------------*/





using namespace std;


void scaleMoveTest(Picture& pic, double scale, double radius, Point center,
		   int numberOfCopies)
{
  Picture accumulated;

  accumulated = pic;

  double totalScale = 1.0;

  // Create multiple copies of pic, each scaled slightly larger and
  // shifted.
  for (int i = 0; i < numberOfCopies; ++i) {
    double angle = double(i) / numberOfCopies * 2.0 * 3.14159;
    Point newPos (center.x() + radius*cos(angle),
		  center.y() + radius*sin(angle));

    Picture shifted;
    shifted = pic;
    shifted.zoom (totalScale);
    totalScale *= scale;
    shifted.translate(newPos.x(), newPos.y());
    accumulated.add(shifted);
  }
  pic = accumulated;
}


void testAllShapes (string fileName)
{
  ifstream in (fileName.c_str());

  Picture p(fileName + ".1.eps");
  Shape* shape;
  while (in >> shape)
    {
      if (shape != NULL)
	p.add(*shape);
    }
  cout << "Bounding box for " << fileName << ".1.eps is "
       << p.boundingBox() << endl;
  p.draw();


  {
    ofstream out ((fileName + ".1.dat").c_str());
    out << p << flush;
  }


  Picture p2 (fileName + ".2.eps");
  p2 = p;
  scaleMoveTest(p2, 0.75, 50, Point(100.0, 100.0), 8);
  cout << "Bounding box for " << fileName << ".2.eps is " << p2.boundingBox() << endl;
  p2.draw();

  {
    ofstream out ((fileName + ".2.dat").c_str());
    out << p2 << flush;
  }


}



void testMovingShapes (string fileName)
{
  ifstream in (fileName.c_str());

  Picture p(fileName + ".3.eps");
  Shape* shape;
  int shapeCount = 0;
  while (in >> shape)
    {
      if (shape != NULL)
	{
	  RectangularArea bb = shape->boundingBox();
	  for (int k = 0; k <= shapeCount; ++k)
	    {
	      Shape* s = shape->clone();
	      s->scale (Point(), 0.25 + 0.5 * k);
	      s->translate(bb.width()*k, bb.height()*k*shapeCount);
	      p.add(*s);
	    }
	  ++shapeCount;
	}
    }
  cout << "Bounding box for " << fileName << ".3.eps is "
       << p.boundingBox() << endl;
  p.draw();


  {
    ofstream out ((fileName + ".3.dat").c_str());
    out << p << flush;
  }
}




int main(int argc, char** argv)
{
  if (argc != 2)
    {
      cerr << "Usage: " << argv[0] << " pictureFileName" << endl;
      return -1;
    }
  testAllShapes(argv[1]);
  testMovingShapes(argv[1]);
  return 0;
}
