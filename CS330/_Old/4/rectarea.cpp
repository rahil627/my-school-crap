#include "point.h"
#include "rectarea.h"
#include <algorithm>

using namespace std;

void
RectangularArea::normalize()
{
  Point ul2 = Point(min(ul.x(), lr.x()),
		    min(ul.y(), lr.y()));
  lr = Point(max(ul.x(), lr.x()),
	     max(ul.y(), lr.y()));
  ul = ul2;
}


RectangularArea::RectangularArea ()
  : ul(Point(-1.0, -1.0)),
    lr(Point(0.0,0.0))
{
}


RectangularArea::RectangularArea (const Point& upperLeft, const Point& lowerRight)
  : ul(upperLeft), lr(lowerRight)
{
  normalize();
}
  
  
RectangularArea::RectangularArea (double ulx, double uly, 
	   double lrx, double lry)
  : ul(Point(ulx, uly)),
    lr(Point(lrx, lry))
{normalize();}

  
  
RectangularArea::
RectangularArea (const Point& upperLeft, 
	   double width, double height)
  : ul(upperLeft),
    lr(Point(ul.x()+width, ul.y()+height))
{
  normalize();
}
  

bool  RectangularArea::contains (Point p) const
{
  return (p.x() >= ul.x()) && (p.x() <= lr.x())
    && (p.y() >= lr.y()) && (p.y() <= ul.y());
}
  
bool  RectangularArea::overlaps (const RectangularArea& r) const
{
  return !(
    isEmpty() || r.isEmpty()
    || (r.lr.y() > ul.y()) || (r.ul.y() < lr.y())
    || (r.lr.x() < ul.x()) || (r.ul.x() > lr.x())
    );
}


RectangularArea  RectangularArea::unionOf (const RectangularArea& r) const
{
  if (isEmpty())
      return r;
  else if (r.isEmpty())
    return *this;
  else
    return RectangularArea(min(ul.x(), r.ul.x()),
		     min(ul.y(), r.ul.y()),
		     max(lr.x(), r.lr.x()),
		     max(lr.y(), r.lr.y()));
}



RectangularArea  RectangularArea::intersection (const RectangularArea& r) const
{
  if (isEmpty())
    return *this;
  else if (r.isEmpty())
    return r;
  else
    return RectangularArea(max(ul.x(), r.ul.x()),
		     max(ul.y(), r.ul.y()),
		     min(lr.x(), r.lr.x()),
		     min(lr.y(), r.lr.y()));
}






