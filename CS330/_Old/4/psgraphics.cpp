#include "psgraphics.h"

#include <fstream>

using namespace std;


/**
 * C++ graphics class for generating PostScript .eps files
 **/

PSGraphics::PSGraphics(std::string fileName,
			 int outputWidth, /* 6 inches */
			 int outputHeight /* 6 inches */
			 )
  : _width(outputWidth), _height(outputHeight),
    epsOut(0), _fileName(fileName),
    _font(Font::Helvetica, Font::PLAIN, 10)
{
  epsOut = new ofstream(fileName.c_str());
  (*epsOut) << "%!PS-ADOBE-3.0\n"
	    << "%%Creator: cs330 PSGraphics class\n"
	    << "%%BoundingBox: 0 0 " <<
                 outputWidth << " " << outputHeight << "\n"
	    << "%%EndComments\n"
	    << "/drawIt {" << endl;

  setClip(0, 0, 10000, 10000);
}


PSGraphics::~PSGraphics()
{
  int w = _boundingBox.lowerRight().x;
  if (w <= 0) w = 1;

  int h = _boundingBox.lowerRight().y;
  if (h <= 0) h = 1;

  (*epsOut) << "} def\n"
	    << "gsave % " << w << " " << h << " " << _width << " " << _height << endl
//	    << double(_width) / w << " " << double(_height) / h 
//    	    << " scale\n"
	    << " drawIt\n"
	    << "grestore" << endl;
  delete epsOut;
}


/**
 * Sets the current clip to the rectangle specified by the given
 * coordinates.
 */
void PSGraphics::setClip(int x, int y, int width, int height)
{
  Graphics::setClip(x,y,width,height);
  (*epsOut) << "newpath % setClip " << x << " " << y
                 << " " << width << " " << height << endl;

  (*epsOut) << x << " " << y << " moveto\n"
	    << "0 " << height << " rlineto\n" 
	    << width << " 0  rlineto\n" 
	    << "0 " << -height << " rlineto\n"
	    << "closepath clip" << endl;
}


/** 
 * Draws a line, using the current color, between the points 
 * (x1,y1) and (x2,y2)
 * in this graphics context's coordinate system. 
 */
void PSGraphics::drawLine(int x1, int y1, int x2, int y2)
{
  (*epsOut) << "newpath % drawLine ("  << x1 << ", " << y1 << 
                             ") (" << x2 << ", " << y2 <<")\n" 

	     << x1 << " " << y1 << " moveto\n"
	     << x2 << " " << y2 << " lineto\n"
	     << "stroke" << endl;

  _boundingBox = _boundingBox.unionOf
    (getClipBounds().intersection(GrRectangularArea(x1,y1,x2,y2)));
}




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
void PSGraphics::fillRect(int x, int y, int width, int height)
{
  (*epsOut) << "newpath % fillRect ("  << x << ", " << y << 
                             ") " << width << ":" << height <<")\n" 
	     << x << " " << y << " moveto\n"
	     << "0 " << height << " rlineto\n" 
	     << width << " 0  rlineto\n" 
	     << "0 " << -height << " rlineto\n"
	     << "closepath fill" << endl;

  _boundingBox = _boundingBox.unionOf
    (getClipBounds().intersection(GrRectangularArea(GrPoint(x,y),width,height)));
}



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
void PSGraphics::drawOval(int x, int y, int width, int height)
{
  (*epsOut) << "newpath % drawOval ("  << x << ", " << y << 
                             ") " << width << ":" << height <<")\n" 
	     << "gsave\n0 setlinewidth\n"
	     << (x + width/2) << " " << (y + height/2) << " translate\n"
	     << width/2 << " " << height/2 << " scale\n" 
	     << "0 0 1 0 360 arc closepath\n"
	     << "stroke grestore" << endl;

  _boundingBox = _boundingBox.unionOf
    (getClipBounds().intersection(GrRectangularArea(GrPoint(x,y),width,height)));
}



/** 
 * Fills an oval bounded by the specified rectangle with the
 * current color.
 */
void PSGraphics::fillOval(int x, int y, int width, int height)
{
  (*epsOut) << "newpath % drawOval ("  << x << ", " << y << 
                             ") " << width << ":" << height <<")\n" 
	    << "gsave\n"
	    << (x + width/2) << " " << (y + height/2) << " translate\n"
	    << width/2 << " " << height/2 << " scale\n" 
	    << "0 0 1 0 360 arc closepath"
	    << " fill grestore" << endl;

  _boundingBox = _boundingBox.unionOf
    (getClipBounds().intersection(GrRectangularArea(GrPoint(x,y),width,height)));
}


/** 
 * Draws a sequence of connected lines defined by 
 * arrays of x and y coordinates. 
 * Each pair of (x, y) coordinates defines a point.
 * The figure is not closed if the first point 
 * differs from the last point.
 */
void PSGraphics::drawPolyline(int* xPoints, int* yPoints,
			      int nPoints)
{
  for (int i = 1; i < nPoints; ++i)
    {
      drawLine (xPoints[i-1], yPoints[i-1],
		xPoints[i], yPoints[i]);
    }
}



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
void PSGraphics::drawPolygon(int* xPoints, int* yPoints,
			     int nPoints)
{
  (*epsOut) << "newpath % drawPolygon "  << nPoints  << "\n"; 
  (*epsOut) << xPoints[0] << " " << yPoints[0] << " moveto\n";

  for (int i = 1; i < nPoints; ++i) {
    (*epsOut) << xPoints[i] << " " << yPoints[i] << " lineto\n";

    _boundingBox = _boundingBox.unionOf
      (getClipBounds().intersection(GrRectangularArea(xPoints[i-1],yPoints[i-1],
				    xPoints[i],yPoints[i])));
  }

  (*epsOut) << "closepath stroke" << endl;
}



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
void PSGraphics::fillPolygon(int* xPoints, int* yPoints,
			     int nPoints)
{
  (*epsOut) << "newpath % drawPolygon "  << nPoints  << "\n"; 
  (*epsOut) << xPoints[0] << " " << yPoints[0] << " moveto\n";

  for (int i = 1; i < nPoints; ++i) {
    (*epsOut) << xPoints[i] << " " << yPoints[i] << " lineto\n";

    _boundingBox = _boundingBox.unionOf
      (getClipBounds().intersection(GrRectangularArea(xPoints[i-1],yPoints[i-1],
					      xPoints[i],yPoints[i])));
  }

  (*epsOut) << "closepath fill" << endl;
}


/** 
 * Draws the text given by the specified string, using this 
 * graphics context's current font and color. The baseline of the 
 * first character is at position (x, y) in this 
 * graphics context's coordinate system. 
 */
void PSGraphics::drawString(std::string str, int x, int y)
{
  (*epsOut) << "newpath % drawString "  << x << " " << y  << "\n" 
	    << x << " " << y << " moveto\n";
  
  setFont(_font);
  string s;
  for (int i = 0; i < str.length(); ++i)
    {
      switch (str[i]) {
      case '\n': s += "\\n"; break;
      case '\r': s += "\\r"; break;
      case '\t': s += "\\t"; break;
      case '\f': s += "\\f"; break;
      case '\\': s += "\\\\"; break;
      case '(': s += "\\("; break;
      case ')': s += "\\)"; break;
      default: s += str[i];
      }
    }
  (*epsOut) << "(" << s << ") show" << endl; 
}


/**
 * Sets this graphics context's font to the specified font. 
 * All subsequent text operations using this graphics context 
 * use this font. 
 */
void PSGraphics::setFont(Font font)
{
  _font = font;

  (*epsOut) << "/" << _font.toString() << " findfont\n"
	    << _font.getSize() << " scalefont setfont"
	    << endl;
}


/**
 * Sets this graphics context's current color to the specified 
 * color. All subsequent graphics operations using this graphics 
 * context use this specified color. 
 */
void PSGraphics::setColor(Color c)
{
  Graphics::setColor(c);
  

  (*epsOut) << c.getRed() / 255.0 << " "
	    << c.getGreen() / 255.0 << " "
	    << c.getBlue() / 255.0 << " "
	    << "setrgbcolor"
	    << endl;
}

