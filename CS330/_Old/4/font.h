#ifndef FONT_H
#define FONT_H

#include <string>


/*
 * Fonts (very limited support)
 * 
 * 
 */

class Font {
public:
  enum Styles {PLAIN=0, BOLD=1, ITALIC=2};
  enum Families {Helvetica=0, Courier=1, TimesRoman=2};

private:
  Styles _style;
  Families _family;
  int _size;
  
public:

  Font(Families family, Styles style, int size)
    : _family(family), _style(style), _size(size)
  { }


  Families getFamily() const {
	return _family;
  }


  Styles getStyle() const {
	return _style;
  }

  int getSize() const {
    return _size;
  }


  std::string toString() const;
};

#endif


