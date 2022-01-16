#include "polygon.h"

using namespace std;


Polygon::Polygon(Color edgeColor, Color fillColor)
/* REMARKS:    Makes an empty polygon
*/
  : Shape(edgeColor, fillColor)
{}

/*.............................................................*/

Point Polygon::corner(int i) const
{  
  return _corners[i];
}

/*.............................................................*/

void Polygon::add_corner(Point p)
{ 
  _corners.push_back(p);
}

/*.............................................................*/

void Polygon::set_corner(int i , Point p)
{ 
  if (0 <= i && i < _corners.size())
    _corners[i] = p;
}

/*.............................................................*/

void Polygon::draw(Graphics& g) const
{  
  if (getEdgeColor() != Color::transparent)
    {
      g.setColor(getEdgeColor());
      int *xPoints = new int[_corners.size()];
      int *yPoints = new int[_corners.size()];
      for (int i = 0; i < _corners.size(); i++)
	{
	  const Point& corner = _corners[i];
	  xPoints[i] = round(corner.x());
	  yPoints[i] = round(corner.y());
	}
      g.drawPolygon (xPoints, yPoints, _corners.size());
      delete [] xPoints;
      delete [] yPoints;
    }
}

void Polygon::fill(Graphics& g) const
{  
  if (getFillColor() != Color::transparent)
    {
      g.setColor(getFillColor());
      int *xPoints = new int[_corners.size()];
      int *yPoints = new int[_corners.size()];
      for (int i = 0; i < _corners.size(); i++)
	{
	  const Point& corner = _corners[i];
	  xPoints[i] = round(corner.x());
       yPoints[i] = round(corner.y());
	}
      g.fillPolygon (xPoints, yPoints, _corners.size());
      delete [] xPoints;
      delete [] yPoints;
    }
}

/*.............................................................*/

void Polygon::print(ostream& os) const
{
  os << "Polygon: " << _corners.size() << " ";
  for (int i = 0; i < _corners.size(); i++)
    {
      if (i > 0)
	os << " ";
     os << _corners[i];
    }
}

/*.............................................................*/

void Polygon::scale(const Point& center, double s)
{  
  for (int i = 0; i < _corners.size(); i++)
    _corners[i] = _corners[i].scale(center, s);
}

/*.............................................................*/

void Polygon::translate(double x, double y)
{   for (int i = 0; i < _corners.size(); i++)
      _corners[i] = _corners[i].translate(x, y);
}

/*.............................................................*/

RectangularArea Polygon::boundingBox() const
{
  if (_corners.size() > 0)
    {
      double xmin = _corners[0].x();
      double xmax = xmin;
      double ymin = _corners[0].y();
      double ymax = ymin;
      for (int i = 1; i < _corners.size(); ++i)
	{
	  Point p = _corners[i];
	  xmin = min(xmin, p.x());
	  xmax = max(xmax, p.x());
	  ymin = min(ymin, p.y());
	  ymax = max(ymax, p.y());
	}
      return RectangularArea(xmin, ymin, xmax, ymax);
    }
  else
    return RectangularArea();
}

/*.............................................................*/

void Polygon::get(std::istream& in)
{
  in >> _edge >> _fill;   // read edge and fill colors
  int nCorners;
  in >> nCorners;
  _corners.clear();
  for (int i = 0; i < nCorners; ++i)
    {
      Point p;
      in >> p;
      _corners.push_back(p);
    }
}
