#include "composite.h"

using namespace std;


Composite::Composite(Color edgeColor, Color fillColor)
  : Shape(edgeColor, fillColor)
{}


Composite::Composite (const Composite& c)
  : Shape(c._edge, c._fill)
{
  for (int i = 0; i < c.shapes.size(); ++i)
    shapes.push_back (c.shapes[i]->clone());
}

Composite& Composite::operator= (const Composite& c)
{
  if (this != &c) 
    {
      setEdgeColor(c.getEdgeColor());
      setFillColor(c.getFillColor());
      for (int i = 0; i < shapes.size(); ++i)
	delete shapes[i];
      shapes.clear();
      for (int i = 0; i < c.shapes.size(); ++i)
	shapes.push_back (c.shapes[i]->clone());
    }
  return *this;
}

Composite::~Composite()
{
  for (int i = 0; i < shapes.size(); ++i)
    delete shapes[i];
}



/*.............................................................*/

void Composite::add(const Shape& s)
{ 
  shapes.push_back(s.clone());
}

/*.............................................................*/

void Composite::plot(Graphics& g) const
{
  if (getFillColor() != Color::transparent)
    {
      g.setColor(getFillColor());
      RectangularArea bb = boundingBox();
      Point ul = bb.upperLeft();
      g.fillRect(round(ul.x()),
		 round(ul.y()),
		 round(bb.width()),
		 round(bb.height()));
    }
  if (getEdgeColor() != Color::transparent)
    {
      g.setColor(getEdgeColor());
      RectangularArea bb = boundingBox();
      Point ul = bb.upperLeft();
      g.drawRect(round(ul.x()),
		 round(ul.y()),
		 round(bb.width()),
		 round(bb.height()));
    }
  for (int i = 0; i < shapes.size(); ++i)
    shapes[i]->plot(g);
}


void Composite::draw(Graphics& g) const
{  
  if (getEdgeColor() != Color::transparent)
    {
      g.setColor(getEdgeColor());
      RectangularArea bb = boundingBox();
      Point ul = bb.upperLeft();
      g.drawRect(round(ul.x()),
		 round(ul.y()),
		 round(bb.width()),
		 round(bb.height()));
    }
  for (int i = 0; i < shapes.size(); ++i)
    shapes[i]->draw(g);
}


void Composite::fill(Graphics& g) const
{  
  if (getFillColor() != Color::transparent)
    {
      g.setColor(getFillColor());
      RectangularArea bb = boundingBox();
      Point ul = bb.upperLeft();
      g.fillRect(round(ul.x()),
		 round(ul.y()),
		 round(bb.width()),
		 round(bb.height()));
    }
  for (int i = 0; i < shapes.size(); ++i)
    shapes[i]->fill(g);
}




/*.............................................................*/

void Composite::print(ostream& os) const
{
  os << "Composite: " << shapes.size() << " {";
  for (int i = 0; i < shapes.size(); i++)
    {
      if (i > 0)
	os << " ";
      shapes[i]->print(os);
    }
  os << "}";
}

/*.............................................................*/

void Composite::scale(const Point& center, double s)
{  
  for (int i = 0; i < shapes.size(); i++)
    shapes[i]->scale(center, s);
}

/*.............................................................*/

void Composite::translate(double x, double y)
{   for (int i = 0; i < shapes.size(); i++)
      shapes[i]->translate(x, y);
}

/*.............................................................*/

RectangularArea Composite::boundingBox() const
{
  RectangularArea bbox;
  if (shapes.size() > 0)
    {
      bbox = shapes[0]->boundingBox();
      for (int i = 1; i < shapes.size(); ++i)
	{
	  bbox = bbox.unionOf(shapes[i]->boundingBox());
	}
      return bbox;
    }
  else
    return bbox;
}

/*.............................................................*/

void Composite::get(std::istream& in)
{
  in >> _edge >> _fill;   // read edge and fill colors
  int nShapes;
  in >> nShapes;
  shapes.clear();
  for (int i = 0; i < nShapes; ++i)
    {
      Shape* s;
      in >> s;
      shapes.push_back(s);
    }
}