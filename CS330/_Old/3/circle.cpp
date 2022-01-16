#include "circle.h"

#include <algorithm>

using namespace std;

Circle::Circle(Color edgeColor, Color fillColor)
 : Shape(edgeColor, fillColor)
{}


Circle::Circle(Point center, double radius, Color edgeColor, Color fillColor)
  : Shape(edgeColor, fillColor), _center(center), _radius(radius)
{}


void Circle::draw(Graphics& g) const
{
  if (getEdgeColor() != Color::transparent)
    {
      g.setColor(getEdgeColor());
      g.drawOval(round(_center.x()-_radius), round(_center.y()-_radius),
		 round(2*_radius), round(2*_radius));
    }
}

void Circle::fill(Graphics& g) const
{
  if (getFillColor() != Color::transparent)
    {
      g.setColor(getFillColor());
      g.fillOval(round(_center.x()-_radius), round(_center.y()-_radius),
		 round(2*_radius), round(2*_radius));
    }
}



void Circle::print(ostream& os) const
{
  os << "Circle: " << _center << " " << _radius;
}



void Circle::scale(const Point& center, double s)
{
  _center = _center.scale(center, s);
  _radius = (s*_radius);
}



void Circle::translate(double x, double y)
{
  _center = _center.translate(x, y);
}



RectangularArea Circle::boundingBox() const
{
  return RectangularArea(Point(_center.x()-_radius,
			       _center.y()-_radius),
			 Point(_center.x()+_radius,
			       _center.y()+_radius));
}

/*.............................................................*/

void Circle::get(std::istream& in)
{
  in >> _edge >> _fill;   // read edge and fill colors
  in >> _center >> _radius;
}