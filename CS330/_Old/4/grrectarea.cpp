#include "point.h"
#include "grrectarea.h"
#include <algorithm>

using namespace std;

void
GrRectangularArea::normalize()
{
  if (ul.x > lr.x)
      swap (ul.x, lr.x);
  if (ul.y > lr.y)
      swap (ul.y, lr.y);
}


GrRectangularArea::GrRectangularArea ()
  : ul(GrPoint(-1, -1)),
    lr(GrPoint(0,0))
{
}


GrRectangularArea::GrRectangularArea (const GrPoint& upperLeft, const GrPoint& lowerRight)
  : ul(upperLeft), lr(lowerRight)
{
  normalize();
}
  
  
GrRectangularArea::GrRectangularArea (int ulx, int uly, 
	   int lrx, int lry)
  : ul(GrPoint(ulx, uly)),
    lr(GrPoint(lrx, lry))
{normalize();}

  
  
GrRectangularArea::
GrRectangularArea (const GrPoint& upperLeft, 
	   int width, int height)
  : ul(upperLeft),
    lr(GrPoint(ul.x+width, ul.y+height))
{
  normalize();
}
  

bool  GrRectangularArea::contains (GrPoint p) const
{
  return (p.x >= ul.x) && (p.x <= lr.x)
    && (p.y >= lr.y) && (p.y <= ul.y);
}
  
bool  GrRectangularArea::overlaps (const GrRectangularArea& r) const
{
  return !(
    isEmpty() || r.isEmpty()
    || (r.lr.y > ul.y) || (r.ul.y < lr.y)
    || (r.lr.x < ul.x) || (r.ul.x > lr.x)
    );
}


GrRectangularArea  GrRectangularArea::unionOf (const GrRectangularArea& r) const
{
  if (isEmpty())
      return r;
  else if (r.isEmpty())
    return *this;
  else
    return GrRectangularArea(min(ul.x, r.ul.x),
		     min(ul.y, r.ul.y),
		     max(lr.x, r.lr.x),
		     max(lr.y, r.lr.y));
}



GrRectangularArea  GrRectangularArea::intersection (const GrRectangularArea& r) const
{
  if (isEmpty())
    return *this;
  else if (r.isEmpty())
    return r;
  else
    return GrRectangularArea(max(ul.x, r.ul.x),
		     max(ul.y, r.ul.y),
		     min(lr.x, r.lr.x),
		     min(lr.y, r.lr.y));
}






