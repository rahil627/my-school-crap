#include "graphics.h"
#include "grrectarea.h"

/**
 * C++ graphics class modelled after the Java java.awt.Graphics
 **/

Graphics::Graphics()
  : _color(Color::black), _clip(0, 0, 100, 100)
{}



/**
 * Sets the current clip to the rectangle specified by the given
 * coordinates.
 */
void Graphics::setClip(int x, int y, int width, int height)
{
  _clip = GrRectangularArea(x,y,width, height);
}



/** 
 * Draws the outline of the specified rectangle. 
 * The left and right edges of the rectangle are at 
 * x and x + width. 
 * The top and bottom edges are at 
 * y and y + height. 
 * The rectangle is drawn using the graphics context's current color.
 */
void Graphics::drawRect(int x, int y, int width, int height)
{
  if ((width < 0) || (height < 0)) {
    return;
  }
  
  if (height == 0 || width == 0) {
    drawLine(x, y, x + width, y + height);
  } else {
    drawLine(x, y, x + width - 1, y);
    drawLine(x + width, y, x + width, y + height - 1);
    drawLine(x + width, y + height, x + 1, y + height);
    drawLine(x, y + height, x, y + 1);
  }
}
    


/**
 * Draws a 3-D highlighted outline of the specified rectangle.
 * The edges of the rectangle are highlighted so that they
 * appear to be beveled and lit from the upper left corner.
 * 
 * The colors used for the highlighting effect are determined 
 * based on the current color.
 * The resulting rectangle covers an area that is 
 * width + 1 pixels wide
 * by height + 1 pixels tall.
 */
void Graphics::draw3DRect(int x, int y, int width, int height,
			  bool raised)
{
  Color c = getColor();
  Color brighter = c.brighter();
  Color darker = c.darker();
  
  setColor(raised ? brighter : darker);
  drawLine(x, y, x, y + height);
  drawLine(x + 1, y, x + width - 1, y);
  setColor(raised ? darker : brighter);
  drawLine(x + 1, y + height, x + width, y + height);
  drawLine(x + width, y, x + width, y + height - 1);
  setColor(c);
}    

/**
 * Paints a 3-D highlighted rectangle filled with the current color.
 * The edges of the rectangle will be highlighted so that it appears
 * as if the edges were beveled and lit from the upper left corner.
 * The colors used for the highlighting effect will be determined from
 * the current color.
 */
void Graphics::fill3DRect(int x, int y, int width, int height,
			  bool raised)
{
  Color c = getColor();
  Color brighter = c.brighter();
  Color darker = c.darker();
  
  if (!raised) {
    setColor(darker);
  }
  fillRect(x+1, y+1, width-2, height-2);
  setColor(raised ? brighter : darker);
  drawLine(x, y, x, y + height - 1);
  drawLine(x + 1, y, x + width - 2, y);
  setColor(raised ? darker : brighter);
  drawLine(x + 1, y + height - 1, x + width - 1, y + height - 1);
  drawLine(x + width - 1, y, x + width - 1, y + height - 2);
  setColor(c);
}    

