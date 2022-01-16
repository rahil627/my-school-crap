#ifndef SHAPE_H
#define SHAPE_H


#include "graphics.h"	
#include "color.h"
#include "rectarea.h"

class Point;

class Shape
{
public:
  Shape (Color edgeColor = Color::black, 
	 Color fillColor = Color::transparent):
    _edge(edgeColor), _fill(fillColor)
    {}

  virtual ~Shape() {}
  
  virtual Shape* clone() const = 0;

  // Get/Set edge and fill colors
  Color getEdgeColor () const;
  void setEdgeColor (Color c);

  Color getFillColor () const;
  void setFillColor (Color c);

  // Transform a shape 
  virtual void scale(const Point& center, double s) = 0;
  virtual void translate(double x, double y) = 0;
  
  // Draw the edge of a shape   
  virtual void draw(Graphics& g) const = 0;

  // Paint the interior of a shape
  virtual void fill(Graphics& g) const = 0;

  virtual void plot(Graphics& g) const {fill(g); draw(g);}

  // Print a description of a shape
  virtual void print(std::ostream& os) const = 0;

  // Compute the smallest rectangular area enclosing this shape.
  virtual RectangularArea boundingBox() const = 0;

  // Read a shape from an input stream
  virtual void get(std::istream& in) = 0;

protected:
  static int round (double d) {return (int)(d + 0.5);}

  Color _edge;
  Color _fill;

};


inline
std::ostream& operator<< (std::ostream& out, const Shape& sh)
{
  sh.print(out);
  return out;
}


std::istream& operator>> (std::istream& in, Shape*& sh);

inline Color Shape::getEdgeColor() const
{
  return _edge;
}

inline Color Shape::getFillColor() const
{
  return _fill;
}

inline void Shape::setEdgeColor(Color c)
{
  _edge = c;
}

inline void Shape::setFillColor(Color c)
{
  _fill = c;
}


#endif
