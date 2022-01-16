#ifndef PSGRAPHICS_H
#define PSGRAPHICS_H


#include "graphics.h"
#include "font.h"

#include <iostream>
#include <string>

/**
 * C++ graphics class for generating PostScript .eps files
 **/

class PSGraphics: public Graphics {
private:

  Font _font;
  GrRectangularArea _boundingBox;
  std::string _fileName;
  std::ostream* epsOut;
  int _width;
  int _height;


public:

  PSGraphics(std::string fileName = "default.eps",
	     int outputWidth = 6*72, /* 6 inches */
	     int outputHeight = 6*72 /* 6 inches */
	     );



  ~PSGraphics();

    /**
     * Gets the current font.
     */
  virtual Font getFont() const {return _font;}

    /**
     * Sets this graphics context's font to the specified font. 
     * All subsequent text operations using this graphics context 
     * use this font. 
    */
  virtual void setFont(Font font);


  /**
     * Sets this graphics context's current color to the specified 
     * color. All subsequent graphics operations using this graphics 
     * context use this specified color. 
     */
  virtual void setColor(Color c);


    /**
     * Sets the current clip to the rectangle specified by the given
     * coordinates.
     */
  virtual void setClip(int x, int y, int width, int height);



    /** 
     * Draws a line, using the current color, between the points 
     * (x1,y1) and (x2,y2)
     * in this graphics context's coordinate system. 
     */
  virtual void drawLine(int x1, int y1, int x2, int y2);

    /** 
     * Fills the specified rectangle. 
     * The left and right edges of the rectangle are at 
     * x and x + width - 1. 
     * The top and bottom edges are at 
     * y and y + height - 1. 
     * The resulting rectangle covers an area 
     * width pixels wide by 
     * height pixels tall.
     * The rectangle is filled using the graphics context's current color. 
     */
  virtual void fillRect(int x, int y, int width, int height);


    /** 
     * Draws the outline of an oval.
     * The result is a circle or ellipse that fits within the 
     * rectangle specified by the x, y, 
     * width, and height arguments. 
     *  
     * The oval covers an area that is 
     * width + 1 pixels wide 
     * and height + 1 pixels tall. 
     */
  virtual void drawOval(int x, int y, int width, int height);

    /** 
     * Fills an oval bounded by the specified rectangle with the
     * current color.
     */
  virtual void fillOval(int x, int y, int width, int height);


    /** 
     * Draws a sequence of connected lines defined by 
     * arrays of x and y coordinates. 
     * Each pair of (x, y) coordinates defines a point.
     * The figure is not closed if the first point 
     * differs from the last point.
     */
  virtual void drawPolyline(int* xPoints, int* yPoints,
			    int nPoints);

    /** 
     * Draws a closed polygon defined by 
     * arrays of x and y coordinates. 
     * Each pair of (x, y) coordinates defines a point.
     * 
     * This method draws the polygon defined by nPoint line 
     * segments, where the first nPoint - 1 
     * line segments are line segments from 
     * (xPoints[i - 1], yPoints[i - 1]) 
     * to (xPoints[i], yPoints[i]), for 
     * 1 <= i <= nPoints.  
     * The figure is automatically closed by drawing a line connecting
     * the final point to the first point, if those points are different.
     */
  virtual void drawPolygon(int* xPoints, int* yPoints,
			   int nPoints);


    /** 
     * Draws a closed, filled polygon defined by 
     * arrays of x and y coordinates. 
     * Each pair of (x, y) coordinates defines a point.
     * 
     * This method draws the polygon defined by nPoint line 
     * segments, where the first nPoint - 1 
     * line segments are line segments from 
     * (xPoints[i - 1], yPoints[i - 1]) 
     * to (xPoints[i], yPoints[i]), for 
     * 1 <= i <= nPoints.  
     * The figure is automatically closed by drawing a line connecting
     * the final point to the first point, if those points are different.
     */
  virtual void fillPolygon(int* xPoints, int* yPoints,
			   int nPoints);


    /** 
     * Draws the text given by the specified string, using this 
     * graphics context's current font and color. The baseline of the 
     * first character is at position (x, y) in this 
     * graphics context's coordinate system. 
     */
  virtual void drawString(std::string str, int x, int y);


};


#endif
