package Pictures;

import java.awt.Color;
import java.awt.Graphics;


abstract public
class Shape implements Cloneable
{

  public Shape (Color color)
    {_color = color;}

  public Shape ()
    {_color = Color.black;}

  public Color getColor()       {return _color;}
  public void putColor(Color c) {_color = c;}

  abstract public void move(int x, int y);
  abstract public void plot(Graphics g);
  abstract public void reflectHorizontally (int xReflectionPlane);
  abstract public void reflectVertically (int yReflectionPlane);

  abstract public java.awt.Rectangle boundingBox();
  // The bounding box is the smallest rectangle containing
  // the entire shape.

  abstract public String toString();
  abstract public Object clone();

  private Color _color;

    // Utility functions used by subclasses
    static java.awt.Point reflectHorizontally(java.awt.Point p,
					      int xReflectionPlane)
    {  
	return new java.awt.Point(p.x + 2*(xReflectionPlane - p.x),
				  p.y);
    }

    static java.awt.Point reflectVertically(java.awt.Point p,
					    int yReflectionPlane)
    {  
	return new java.awt.Point(p.x,
				  p.y + 2*(yReflectionPlane - p.y));
    }


};

