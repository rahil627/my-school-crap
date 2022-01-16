#include "picture.h"
#include "shape.h"
#include "point.h"
#include "psgraphics.h"

using namespace std;

Picture::Picture(std::string fileName):
  shapes(0), lastShape(0), gd(new PSGraphics(fileName.c_str()))
{
}

Picture::Picture():
  shapes(0), lastShape(0), gd(0)
{
}

  
Picture::~Picture()
{
  clear();
  delete gd;
}


Picture& Picture::operator= (const Picture& p)
{
  if (p.shapes != shapes)
    {
      clear();
      for (ShapeList current = p.shapes; current != 0; current = current->next)
        add (*(current->shape));
    }
  return *this;
}



void Picture::clear()
{
  ShapeList next;
  
  while (shapes != 0) 
    {
      next = shapes->next;
      delete shapes->shape;
      delete shapes;
      shapes = next;
    }
  lastShape = 0;
}

  
void Picture::add (const Shape& s)
{
  ShapeList ss = new ShapeListNode(s.clone(), 0);

  if (lastShape == 0)
    shapes = lastShape = ss;
  else
    {
      lastShape->next = ss;
      lastShape = ss;
    }
}


void Picture::add (const Picture& p)
{
  for (ShapeList current = p.shapes; current != 0; current = current->next)
    add (*(current->shape));
}


void Picture::draw() const
{
  if (gd != 0)
    {
      for (ShapeList current = shapes; current != 0; current = current->next)
	current->shape->plot(*gd);
    }
}



void Picture::zoom(double factor)
{
  Point origin (0, 0);
  for (ShapeList current = shapes; current != 0; current = current->next)
      current->shape->scale(origin, factor);
}

void Picture::translate(double deltaX, double deltaY)
{
  for (ShapeList current = shapes; current != 0; current = current->next)
    current->shape->translate(deltaX, deltaY);
}



void Picture::put(std::ostream& out) const
{
  for (ShapeList current = shapes; current != 0; current = current->next)
      {
	current->shape->print(out);
        out << endl;
      }
}


RectangularArea Picture::boundingBox() const
{
  RectangularArea bbox;
  if (shapes != 0) 
    {
      bbox = shapes->shape->boundingBox();
      for (ShapeList current = shapes->next; 
	   current != 0; current = current->next)
	{
	  bbox = bbox.unionOf(current->shape->boundingBox());
	}
    }
  return bbox;
}
