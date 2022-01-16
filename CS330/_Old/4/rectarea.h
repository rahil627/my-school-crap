#ifndef RECTAREA_H
#define RECTAREA_H

#include "point.h"
#include <iostream>

class RectangularArea
{
private:
  Point ul;
  Point lr;

  void normalize();

public:
  RectangularArea();

  RectangularArea (const Point& upperLeft, const Point& lowerRight);
  
  RectangularArea (double ulx, double uly, 
             double lrx, double lry);
  
  RectangularArea (const Point& upperLeft, 
             double width, double height);
  

  Point upperLeft() const  {return ul;}
  Point upperRight() const {return Point(lr.x(), ul.y());}
  Point lowerLeft() const  {return Point(ul.x(), lr.y());}
  Point lowerRight() const {return lr;}

  double width() const  {return lr.x() - ul.x();}
  double height() const {return lr.y() - ul.y();}

  bool isEmpty() const;

  bool contains (Point p) const;
	
  bool overlaps (const RectangularArea& r) const;

  RectangularArea intersection (const RectangularArea& r) const;
  RectangularArea unionOf (const RectangularArea& r) const;
};


inline  
bool  RectangularArea::
isEmpty() const
{ 
  return (ul.x() > lr.x()) || (ul.y() > lr.y());
}


inline
std::ostream& operator<< (std::ostream& out, const RectangularArea& r)
{
  out << "[" << r.upperLeft() << "," << r.lowerRight() << "]";
  return out;
}

#endif
