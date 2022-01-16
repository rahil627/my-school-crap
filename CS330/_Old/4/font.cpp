#include "font.h"

using namespace std;

std::string Font::toString() const
{
  static char* FamilyNames[] = {"Helvectica", "Courier", "Times-Roman"};
  static char* StyleNames[] = {"", "-Bold", "-Oblique"};
  return string(FamilyNames[_family]) + string(StyleNames[_style]);
}
