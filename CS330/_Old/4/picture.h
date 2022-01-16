#ifndef PICTURE_H
#define PICTURE_H

#include <iostream>
#include <string>
#include "graphics.h"
#include "rectarea.h"

class Shape;




class Picture {
public:

  // Create a new picture, writing its graphics into the
  // indicated file.
  Picture(std::string fileName);

  // Create a picture to serve as a holder for a collection of shapes.
  // This picture cannot be drawn.
  Picture();

  virtual ~Picture();

  // Set this picture to contain (copies of) all shapes of another,
  // without affecting the file name associated with either picture.
  Picture& operator= (const Picture&);
  
  // Remove all shapes form the picture
  void clear();

  // Add a shape
  void add (const Shape&);

  // Add all shapes from another picture
  void add (const Picture&);

  // Draw the currrent picture to the file.
  void draw() const;

  // Write a description of all the shapes
  void put(std::ostream& out) const;
   
  // Shrink or magnify the picture by the
  // indicated amount (by scaling each shape relative to
  // the origin (0,0)).
  void zoom (double size);

  // Shift every shape in the picture by the indicated
  // indicated amount in the x and y directions.
  void translate (double deltaX, double deltaY);


  // Compute the smallest rectangular area that encloses all
  // shapes in the picture
  RectangularArea boundingBox() const;

private:
  struct ShapeListNode;
  typedef ShapeListNode *ShapeList;
  


  struct ShapeListNode {
    Shape *shape;
    ShapeList next;

    ShapeListNode () : shape(0), next(0) {}
    ShapeListNode (Shape* sh, ShapeList nxt) : shape(sh), next(nxt) {}
  };


  ShapeList shapes;
  ShapeList lastShape;
  Graphics* gd;

  // Copy constructor is hidden, because we don't want two  
  // pictures associated with the same file name.
  Picture (const Picture&) {}

};


inline
std::ostream& operator<< (std::ostream& out, const Picture& p)
{
  p.put(out);
  return out;
}

#endif
