#ifndef COLOR_H
#define COLOR_H

#include <iostream>

/**
 * Color class: degined to mimic java.awt.Color
 * 
 * This class describes colors using the RGB format. In RGB 
 * format, the red, blue, and green components of a color are each 
 * represented by an integer in the range 0-255. The value 0 
 * indicates no contribution from this primary color. The value 255 
 * indicates the maximum intensity of this color component. 
 */



class Color {
private:
  unsigned short int _red, _blue, _green;
  bool _transparent;    
  
  Color (bool tr): _transparent(true), _red(0), _blue(0), _green(0) {}
  
public:
  // predefined colors
  static const Color white;
  static const Color lightGray;
  static const Color gray;
  static const Color darkGray;
  static const Color black;
  static const Color red;
  static const Color pink;
  static const Color orange;
  static const Color yellow;
  static const Color green;
  static const Color magenta;
  static const Color cyan;
  static const Color blue;

  static const Color transparent;


  /**
   * Creates a color with the specified red, green, and blue 
   * components. The three arguments must each be in the range 
   * 0-255. 
   */
  Color(int r, int g, int b);

  /**
   * Equvalent to Color(0,0,0)
   */
  Color();


  /**
   * Creates a color with the specified red, green, and blue values, 
   * where each of the values is in the range 0.0-1.0. The value 
   * 0.0 indicates no contribution from the primary color component. 
   * The value 1.0 indicates the maximum intensity of the primary color 
   * component. 
   */

  Color(double r, double g, double b);

  /**
   * Gets the red component of this color. The result is 
   * an integer in the range 0 to 255. 
   */
  int getRed() const { return _red; }

  /**
   * Gets the green component of this color. The result is 
   * an integer in the range 0 to 255. 
   */
  int getGreen() const { return _green; }

  /**
   * Gets the blue component of this color. The result is 
   * an integer in the range 0 to 255. 
   */
  int getBlue() const { return _blue; }



  /**
   * Creates a brighter version of this color.
   *
   * This method applies an arbitrary scale factor to each of the three RGB 
   * components of the color to create a brighter version of the same 
   * color. Although <code>brighter</code> and <code>darker</code> are 
   * inverse operations, the results of a series of invocations of 
   * these two methods may be inconsistent because of rounding errors. 
   */
  Color brighter() const;

  /**
   * Creates a darker version of this color.
   *
   * This method applies an arbitrary scale factor to each of the three RGB 
   * components of the color to create a darker version of the same 
   * color. Although <code>brighter</code> and <code>darker</code> are 
   * inverse operations, the results of a series of invocations of 
   * these two methods may be inconsistent because of rounding errors. 
   */
  Color darker() const;



  bool operator== (const Color& c) {
    return (_transparent && c._transparent) ||
      (_transparent == c._transparent && 
       _red == c._red && _green == c._green && _blue == c._blue);
  }
    
    
  bool operator!= (const Color& c) {
    return !operator==(c);
  }
  
  
  void put (std::ostream& out) const;
  
};

inline 
std::ostream& operator<< (std::ostream& out, Color c)
{
  c.put(out);
  return out;
}

std::istream& operator>> (std::istream& in, Color& c);

#endif
