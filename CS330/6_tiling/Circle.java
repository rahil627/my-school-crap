package Pictures;

import java.awt.Color;
import java.awt.Graphics;

class Circle extends Shape
{
    public Circle(Color c)
    {
	super(c);
	_center = new java.awt.Point(0, 0);
	_radius = 0;
    }
       
   public Circle()
    {
	super(Color.black);
	_center = new java.awt.Point(0, 0);
	_radius = 0;
    }

   public Circle(java.awt.Point center, int radius)
    {
	super(Color.black);
	_center = (java.awt.Point)center.clone();
	_radius = radius;
    }

   public Circle(java.awt.Point center, int radius, Color c)
    {
	super(c);
	_center = (java.awt.Point)center.clone();
	_radius = radius;
    }


   public void move(int x, int y)
    {
	_center.x += x;
	_center.y += y;
    }

   public void plot(Graphics g)
    {
	g.setColor(getColor());
	g.drawOval(_center.x-_radius, _center.y-_radius,
		   2*_radius, 2*_radius);
    }

   public void reflectHorizontally (int xReflectionPlane)
    {
	_center = Shape.reflectHorizontally(_center, xReflectionPlane);
    }

   public void reflectVertically (int yReflectionPlane)
    {
	_center = Shape.reflectVertically(_center, yReflectionPlane);
    }

   public java.awt.Rectangle boundingBox()
    {
	return new java.awt.Rectangle(_center.x - _radius,
				      _center.y - _radius,
				      2*_radius, 2*_radius);
    }

   public String toString()
    {
	return "Circle: " + new Point(_center) + " " + _radius;
    }

   public Object clone() 
    {
	return new Circle(_center, _radius, getColor());
    }

private java.awt.Point _center;
private int   _radius;
};

