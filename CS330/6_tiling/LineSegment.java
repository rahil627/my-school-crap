package Pictures;

import java.awt.Color;
import java.awt.Graphics;

class LineSegment extends Shape
{
    public LineSegment(Color c)
    {
	super(c);
	_p1 = new java.awt.Point(0,0);
	_p2 = new java.awt.Point(0,0);
    }
       
   public LineSegment()
    {
	super(Color.black);
	_p1 = new java.awt.Point(0,0);
	_p2 = new java.awt.Point(0,0);
    }

   public LineSegment(java.awt.Point p1, java.awt.Point p2)
    {
	super(Color.black);
	_p1 = (java.awt.Point)p1.clone();
	_p2 = (java.awt.Point)p2.clone();
    }

   public LineSegment(java.awt.Point p1, java.awt.Point p2, Color c)
    {
	super(c);
	_p1 = (java.awt.Point)p1.clone();
	_p2 = (java.awt.Point)p2.clone();
    }


   public void move(int x, int y)
    {
	_p1.x += x;
	_p1.y += y;
	_p2.x += x;
	_p2.y += y;
    }

   public void plot(Graphics g)
    {
	g.setColor(getColor());
	g.drawLine(_p1.x, _p1.y, _p2.x, _p2.y);
    }

   public void reflectHorizontally (int xReflectionPlane)
    {
	_p1 = Shape.reflectHorizontally(_p1, xReflectionPlane);
	_p2 = Shape.reflectHorizontally(_p2, xReflectionPlane);
    }

   public void reflectVertically (int yReflectionPlane)
    {
	_p1 = Shape.reflectVertically(_p1, yReflectionPlane);
	_p2 = Shape.reflectVertically(_p2, yReflectionPlane);
    }


   public java.awt.Rectangle boundingBox()
    {
	java.awt.Point ll = new java.awt.Point();
	java.awt.Point ur = new java.awt.Point();
	if (_p1.x < _p2.x) {
	    ll.x = _p1.x;
	    ur.x = _p2.x;
	} else {
	    ll.x = _p2.x;
	    ur.x = _p1.x;
	}
	if (_p1.y < _p2.y) {
	    ll.y = _p1.y;
	    ur.y = _p2.y;
	} else {
	    ll.y = _p2.y;
	    ur.y = _p1.y;
	}

	return new java.awt.Rectangle(ll.x, ll.y, ur.x-ll.x, ur.y-ll.y);
    }


   public String toString()
    {
	return "LineSegment: (" + _p1.x + "," + _p1.y + ") ("
	    + _p2.x + "," + _p2.y + ")";
    }


   public Object clone() 
    {
	return new LineSegment(_p1, _p2, getColor());
    }


    private java.awt.Point   _p1;
    private java.awt.Point   _p2;

};

