package Pictures;

import java.awt.Color;
import java.awt.Graphics;


class Point extends Shape
{
    public Point(Color c)
    {
	super(c);
	_coordinates = new java.awt.Point(0, 0);
    }
       
   public Point()
    {
	super(Color.black);
	_coordinates = new java.awt.Point(0, 0);
    }

   public Point(java.awt.Point coordinates)
    {
	super(Color.black);
	_coordinates = (java.awt.Point)coordinates.clone();
    }

   public Point(java.awt.Point coordinates, Color c)
    {
	super(c);
	_coordinates = (java.awt.Point)coordinates.clone();
    }


   public void move(int x, int y)
    {
	_coordinates.x += x;
	_coordinates.y += y;
    }

   public void plot(Graphics g)
    {
	g.setColor(getColor());
	g.fillOval(_coordinates.x, _coordinates.y, 1, 1);
    }

   public void reflectHorizontally (int xReflectionPlane)
    {
	_coordinates = Shape.reflectHorizontally(_coordinates,
						 xReflectionPlane);
    }

   public void reflectVertically (int yReflectionPlane)
    {
	_coordinates = Shape.reflectVertically(_coordinates,
					       yReflectionPlane);
    }

   public java.awt.Rectangle boundingBox()
    {
	return new java.awt.Rectangle(_coordinates.x,
				      _coordinates.y, 1, 1);
    }

   public String toString()
    {
	return "(" + _coordinates.x + "," + _coordinates.y + ")";
    }

   public Object clone() 
    {
	return new Point(_coordinates, getColor());
    }

private java.awt.Point _coordinates;

};

