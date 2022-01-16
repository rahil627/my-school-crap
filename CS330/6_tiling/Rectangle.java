package Pictures;

import java.awt.Color;
import java.awt.Graphics;

class Rectangle extends Shape
{
    public Rectangle(Color c)
    {
	super(c);
	_rect = new java.awt.Rectangle();
    }
       
   public Rectangle()
    {
	super(Color.black);
	_rect = new java.awt.Rectangle();
    }

   public Rectangle(java.awt.Point p1, java.awt.Point p2)
    {
	super(Color.black);
	setCorners (p1, p2);
    }

   public Rectangle(java.awt.Point p1, java.awt.Point p2, Color c)
    {
	super(c);
	setCorners (p1, p2);
    }


   public void move(int x, int y)
    {
	_rect.x += x;
	_rect.y += y;
    }

   public void plot(Graphics g)
    {
	g.setColor(getColor());
	g.drawRect(_rect.x, _rect.y, _rect.width, _rect.height);
    }

   public void reflectHorizontally (int xReflectionPlane)
    {
	java.awt.Point p1
	    = Shape.reflectHorizontally(new java.awt.Point(_rect.x, _rect.y),
					xReflectionPlane);

	java.awt.Point p2
	    = Shape.reflectHorizontally(new java.awt.Point
					  (_rect.x+_rect.width,
					   _rect.y+_rect.height),
					xReflectionPlane);
	setCorners (p1, p2);
    }

   public void reflectVertically (int yReflectionPlane)
    {
	java.awt.Point p1
	    = Shape.reflectVertically(new java.awt.Point(_rect.x, _rect.y),
					yReflectionPlane);

	java.awt.Point p2
	    = Shape.reflectVertically(new java.awt.Point
				        (_rect.x+_rect.width,
					 _rect.y+_rect.height),
					yReflectionPlane);
	setCorners (p1, p2);
    }

   public java.awt.Rectangle boundingBox()
    {
	return (java.awt.Rectangle)_rect.clone();
    }

   public String toString()
    {
	return "Rectangle: (" + _rect.x + "," + _rect.y + ") +"
	    + _rect.width + "+" + _rect.height;
    }

   public Object clone() 
    {
	Rectangle copy = new Rectangle(getColor());
	copy._rect = (java.awt.Rectangle)_rect.clone();
	return copy;
    }


    private java.awt.Rectangle   _rect;

    private void setCorners (java.awt.Point p1, java.awt.Point p2)
    {
	if (p1.x > p2.x) {
	    int temp = p1.x;
	    p1.x = p2.x;
	    p2.x = temp;
	}
	if (p1.y > p2.y) {
	    int temp = p1.y;
	    p1.y = p2.y;
	    p2.y = temp;
	}
	_rect = new java.awt.Rectangle(p1.x, p1.y, p2.x-p1.x, p2.y-p1.y);
    }
};

