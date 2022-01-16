#ifndef COMPOSITE_H
#define COMPOSITE_H



#include "shape.h"
#include "point.h"
#include <vector>

class Composite : public Shape
{
public:
  Composite(Color edgeColor = Color::black,
	  Color fillColor = Color::transparent);

  Composite (const Composite&);
  Composite& operator= (const Composite&);
  ~Composite();


  void add(const Shape&);
  int numberOfShapes () const {return shapes.size();}

  virtual void scale(const Point& center, double s);
  virtual void translate(double x, double y);
  virtual void draw(Graphics& g) const;
  virtual void fill(Graphics& g) const;
  virtual void plot(Graphics& g) const;
  virtual void print(std::ostream& os) const;

  virtual Shape* clone() const {return new Composite(*this);}

  virtual RectangularArea boundingBox() const;
  virtual void get(std::istream& in);

private:
  std::vector<Shape*> shapes;
};

#endif