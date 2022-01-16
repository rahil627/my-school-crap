#include "line.h"

#include <algorithm>

using namespace std;



/*-------------------------------------------------------------*/

Line::Line(Color edgeColor, Color fillColor)
  : Shape(edgeColor, fillColor)
{}

/*.............................................................*/

Line::Line(Point end1, Point end2, 
		     Color edgeColor, Color fillColor)
  : Shape(edgeColor, fillColor),
    _end1(end1), _end2(end2)
{
}

/*.............................................................*/

void Line::draw(Graphics& g) const
{  
  if (getEdgeColor() != Color::transparent)
    {
      g.setColor(getEdgeColor());
      g.drawLine(round(_end1.x()),
		 round(_end1.y()),
		 round(_end2.x()),
		 round(_end2.y()));
    }
}

/*.............................................................*/

void Line::fill(Graphics& g) const
{  
}

/*.............................................................*/

void Line::print(ostream& os) const
{
  os << "Line: " << _end1 << ":" << _end2;
}

/*.............................................................*/

void Line::scale(const Point& center, double s)
{
  _end1 = _end1.scale(center, s);
  _end2 = _end2.scale(center, s);
}

/*.............................................................*/

void Line::translate(double x, double y)
{
  _end1 = _end1.translate(x, y);
  _end2 = _end2.translate(x, y);
}

/*.............................................................*/

Point Line::topleft() const
{
  return _end1;
}

/*.............................................................*/

Point Line::bottomright() const
{
  return _end2;
}


/*.............................................................*/

RectangularArea Line::boundingBox() const
{
  return RectangularArea(_end1, _end2);
}

/*.............................................................*/

void Line::get(std::istream& in)
{
  in >> _edge >> _fill;   // read edge and fill colors
  in >> _end1 >> _end2;
}
