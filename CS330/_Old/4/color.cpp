#include "color.h"
#include <cassert>
#include <algorithm>



using namespace std;

/**
 * Color class: degined to mimic java.awt.Color
 * 
 * This class describes colors using the RGB format. In RGB 
 * format, the red, blue, and green components of a color are each 
 * represented by an integer in the range 0-255. The value 0 
 * indicates no contribution from this primary color. The value 255 
 * indicates the maximum intensity of this color component. 
 */



const Color Color::white (255, 255, 255);
const Color Color::lightGray (192, 192, 192);
const Color Color::gray (128, 128, 128);
const Color Color::darkGray (64, 64, 64);
const Color Color::black (0, 0, 0);
const Color Color::red (255, 0, 0);
const Color Color::pink (255, 175, 175);
const Color Color::orange (255, 200, 0);
const Color Color::yellow (255, 255, 0);
const Color Color::green (0, 255, 0);
const Color Color::magenta (255, 0, 255);
const Color Color::cyan (0, 255, 255);
const Color Color::blue (0, 0, 255);

const Color Color::transparent (true);


Color::Color()
  :_red(0), _green(0), _blue(0), _transparent(false)
{}

Color::Color(int r, int g, int b)
  :_red(r), _green(g), _blue(b), _transparent(false)
{
  assert (r >= 0 && r <= 255);
  assert (g >= 0 && g <= 255);
  assert (b >= 0 && b <= 255);
}


Color::Color(double r, double g, double b)
  : _red((short unsigned)(r*255 + 0.5)),
    _green((short unsigned)(g*255 + 0.5)),
    _blue((short unsigned)(b*255 + 0.5))
{
  assert (r >= 0.0 && r <= 1.0);
  assert (g >= 0.0 && g <= 1.0);
  assert (b >= 0.0 && b <= 1.0);
}


Color Color::brighter() const
{
  const double FACTOR = 0.7;
  return Color(min((int)(getRed()  *(1/FACTOR)), 255), 
	       min((int)(getGreen()*(1/FACTOR)), 255),
	       min((int)(getBlue() *(1/FACTOR)), 255));
}


Color Color::darker() const 
{
  const double FACTOR = 0.7;
  return Color(getRed()*FACTOR/255.0, 
	       getGreen()*FACTOR/255.0,
	       getBlue()*FACTOR/255.0);
}


void Color::put (ostream& out) const
{
  if (_transparent)
    out << "(x,x,x)";
  else
     out <<  "[r:" << getRed()
	 << ", g:" << getGreen()
	 << ", b:" << getBlue() << "]";
}


std::istream& operator>> (std::istream& in, Color& c)
{
  // input format is three numbers each preceded by
  // any number of characters ending in a ':' and the last followed
  // one non-blank character.  (Compare to output format above).
  int r, g, b;
  char ch = ' ';
  while (in >> ch && ch != ':') ;
  in >> r;
  while (in >> ch && ch != ':') ;
  in >> g;
  while (in >> ch && ch != ':') ;
  in >> b;
  in >> ch;
  if (r >= 0 && g >= 0 && b >= 0)
    c = Color(r, g, b);
  else
    c = Color::transparent;
  return in;
}
