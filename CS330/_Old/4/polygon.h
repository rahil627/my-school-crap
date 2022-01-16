#ifndef POLYGON_H
#define POLYGON_H



#include "shape.h"
#include "point.h"
#include <vector>

class Polygon : public Shape
{
public:
  Polygon(Color edgeColor = Color::black,
	  Color fillColor = Color::transparent);
  
  void add_corner(Point);
  void set_corner(int cornerNumber, Point);
  Point corner(int cornerNumber) const;

  int numberOfCorners () const {return _corners.size();}

  virtual void scale(const Point& center, double s);
  virtual void translate(double x, double y);
  virtual void draw(Graphics& g) const;
  virtual void fill(Graphics& g) const;
  virtual void print(std::ostream& os) const;
  
  virtual Shape* clone() const {return new Polygon(*this);}
  
  virtual RectangularArea boundingBox() const;
  virtual void get(std::istream& in);
  
private:
  std::vector<Point> _corners;
};

#endif
