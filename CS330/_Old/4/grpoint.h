#ifndef GRPOINT_H
#define GRPOINT_H

#include <iostream>

struct GrPoint 
{
  int x, y;

  GrPoint(): x(0), y(0) {}
  GrPoint (int xx, int yy): x(xx), y(yy) {}

};

inline
std::ostream& operator<< (std::ostream& out, const GrPoint& p)
{
  out << "(" << p.x << "," << p.y << ")";
  return out;
}

inline
bool operator== (GrPoint p, GrPoint q)
{
  return (p.x == q.x) && (p.y == q.y);
}

inline
bool operator!= (GrPoint p, GrPoint q)
{
  return !(p == q);
}

#endif
