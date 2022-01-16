#ifndef LINE_H
#define LINE_H


#include "shape.h"	
#include "point.h"	


class Line : public Shape
{
public:
  Line(Color edgeColor = Color::black,
	    Color fillColor = Color::transparent);
  Line(Point end1, Point end2, 
	    Color edgeColor = Color::black,
	    Color fillColor = Color::transparent);


  virtual void scale(const Point& center, double s);
  virtual void translate(double x, double y);
  virtual void draw(Graphics& g) const;
  virtual void fill(Graphics& g) const;
  virtual void print(std::ostream& os) const;
  
  Point topleft() const;
  Point bottomright() const;
  
  virtual Shape* clone() const {return new Line(*this);}
  
  virtual RectangularArea boundingBox() const;
  virtual void get(std::istream& in);

private:
  void normalize();

  Point _end1, _end2;
};

#endif
