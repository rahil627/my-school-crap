#include "rectangle.h"

#include <algorithm>

using namespace std;



/*-------------------------------------------------------------*/

Rectangle::Rectangle(Color edgeColor, Color fillColor)
  : Shape(edgeColor, fillColor)
{}

/*.............................................................*/

void Rectangle::normalize()
{
  Point p = Point(min(_topleft.x(), _bottomright.x()),
		  min(_topleft.y(), _bottomright.y()));
  _bottomright = Point(max(_topleft.x(), _bottomright.x()),
		       max(_topleft.y(), _bottomright.y()));
  _topleft = p;
}

/*.............................................................*/

Rectangle::Rectangle(Point corner1, Point corner2, 
		     Color edgeColor, Color fillColor)
  : Shape(edgeColor, fillColor),
    _topleft(corner1), _bottomright(corner2)
{
  normalize();
}

/*.............................................................*/

void Rectangle::draw(Graphics& g) const
{  
  if (getEdgeColor() != Color::transparent)
    {
      g.setColor(getEdgeColor());
      g.drawRect(round(_topleft.x()),
		 round(_topleft.y()),
		 round(_bottomright.x() - _topleft.x()),
		 round(_bottomright.y() - _topleft.y()));
    }
}

/*.............................................................*/

void Rectangle::fill(Graphics& g) const
{  
  if (getFillColor() != Color::transparent)
   {
     g.setColor(getFillColor());
     g.fillRect(round(_topleft.x()),
		round(_topleft.y()),
		round(_bottomright.x() - _topleft.x()),
		round(_bottomright.y() - _topleft.y()));
   }
}

/*.............................................................*/

void Rectangle::print(ostream& os) const
{
  os << "Rectangle: " << _topleft << ":" << _bottomright;
}

/*.............................................................*/

void Rectangle::scale(const Point& center, double s)
{
  _topleft = _topleft.scale(center, s);
  _bottomright = _bottomright.scale(center, s);
}

/*.............................................................*/

void Rectangle::translate(double x, double y)
{
  _topleft = _topleft.translate(x, y);
  _bottomright = _bottomright.translate(x, y);
}

/*.............................................................*/

Point Rectangle::topleft() const
{
  return _topleft;
}

/*.............................................................*/

Point Rectangle::bottomright() const
{
  return _bottomright;
}


/*.............................................................*/

RectangularArea Rectangle::boundingBox() const
{
  return RectangularArea(_topleft, _bottomright);
}

/*.............................................................*/

void Rectangle::get(std::istream& in)
{
  in >> _edge >> _fill;   // read edge and fill colors
  in >> _topleft >> _bottomright;
  normalize();
}
