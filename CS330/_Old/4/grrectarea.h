#ifndef GRRECTAREA_H
#define GRRECTAREA_H

#include "grpoint.h"
#include <iostream>

class GrRectangularArea
{
private:
  GrPoint ul;
  GrPoint lr;

  void normalize();

public:
  GrRectangularArea();

  GrRectangularArea (const GrPoint& upperLeft, const GrPoint& lowerRight);
  
  GrRectangularArea (int ulx, int uly, 
             int lrx, int lry);
  
  GrRectangularArea (const GrPoint& upperLeft, 
             int width, int height);
  

  GrPoint upperLeft() const  {return ul;}
  GrPoint upperRight() const {return GrPoint(lr.x, ul.y);}
  GrPoint lowerLeft() const  {return GrPoint(ul.x, lr.y);}
  GrPoint lowerRight() const {return lr;}

  int width() const  {return lr.x - ul.x;}
  int height() const {return lr.y - ul.y;}

  bool isEmpty() const;

  bool contains (GrPoint p) const;
	
  bool overlaps (const GrRectangularArea& r) const;

  GrRectangularArea intersection (const GrRectangularArea& r) const;
  GrRectangularArea unionOf (const GrRectangularArea& r) const;
};


inline  
bool  GrRectangularArea::
isEmpty() const
{ 
  return (ul.x > lr.x) || (ul.y > lr.y);
}


inline
std::ostream& operator<< (std::ostream& out, const GrRectangularArea& r)
{
  out << "[" << r.upperLeft() << "," << r.lowerRight() << "]";
  return out;
}

#endif
