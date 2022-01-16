#ifndef RECTANGLE_H
#define RECTANGLE_H


#include "shape.h"	
#include "point.h"	


class Rectangle : public Shape
{
public:
  Rectangle(Color edgeColor = Color::black,
	    Color fillColor = Color::transparent);
  Rectangle(Point corner1, Point corner2, 
	    Color edgeColor = Color::black,
	    Color fillColor = Color::transparent);


  virtual void scale(const Point& center, double s);
  virtual void translate(double x, double y);
  virtual void draw(Graphics& g) const;
  virtual void fill(Graphics& g) const;
  virtual void print(std::ostream& os) const;
  
  Point topleft() const;
  Point bottomright() const;
  
  virtual Shape* clone() const {return new Rectangle(*this);}
  
  virtual RectangularArea boundingBox() const;
  virtual void get(std::istream& in);

private:
  void normalize();

  Point _topleft;
  Point _bottomright;
};

#endif
