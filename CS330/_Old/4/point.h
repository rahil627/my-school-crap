#ifndef POINT_H
#define POINT_H

#include "graphics.h"	


class Point
{
public:
  Point(double xx =0, double yy =0) : _x(xx), _y(yy) {}


  Point scale(const Point& center, double s) const;
  Point translate (double x, double y) const;

  double x() const;
  double y() const;

  bool operator== (const Point& p) const
    {return x() == p.x() && y() == p.y();}

  bool operator!= (const Point& p) const
    {return !(operator==(p));}

  double distanceFrom (const Point& p);

private:
   double _x;
   double _y;
};


std::ostream& operator<< (std::ostream& out, Point p);
std::istream& operator>> (std::istream& in, Point& p);


/*.............................................................*/

inline double Point::x() const
{
  return _x;
}


inline double Point::y() const
{
  return _y;
}


#endif

