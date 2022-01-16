#include "point.h"
#include <cstdlib>
#include <cmath>

using namespace std;




ostream& operator<< (ostream& out, Point p)
{
  out << "(" << p.x() << "," << p.y() << ")";
  return out;
}


istream& operator>> (istream& in, Point& p)
{
  char c;
  double x, y;
  in >> c >>  x >> c >> y >> c;
  p = Point(x,y);
  return in;
}


Point Point::scale(const Point& center, double s) const
{
  return Point(
	       center._x + ((_x - center._x) * s),
	       center._y + ((_y - center._y) * s));
}



Point Point::translate(double x, double y) const
{
  return Point (_x + x, _y + y);
}



double Point::distanceFrom (const Point& p)
{
  double dx = _x - p._x;
  double dy = _y - p._y;
  return sqrt(dx*dx + dy*dy);
}
