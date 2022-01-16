package Pictures;

import java.awt.Color;
import java.awt.Graphics;

class Polygon extends Shape
{
  public Polygon(int n, Color c)
  {
    super(c);
    _corners = new java.awt.Point[n];
  }
  
  public Polygon(int n)
  {
    super(Color.black);
    _corners = new java.awt.Point[n];
  }
  
  
  public void set_corner(int k, java.awt.Point p)
  {
    _corners[k] = (java.awt.Point)p.clone();
  }
  
  public java.awt.Point corner(int k)
  {
    return _corners[k];
  }

  public void move(int x, int y)
  {
    for (int i = 0; i < _corners.length; ++i) {
      _corners[i].x += x;
      _corners[i].y += y;
    }
  }

  public void plot(Graphics g)
  {
	g.setColor(getColor());
    for (int i = 0; i < _corners.length; i++) {
      int j = i + 1;
      if (j >= _corners.length)
        j = 0;
      java.awt.Point p = _corners[i];
      java.awt.Point q = _corners[j];
      g.drawLine(p.x, p.y, q.x, q.y);
    }
  }

  public void reflectHorizontally (int xReflectionPlane)
  {
    for (int i = 0; i < _corners.length; i++)
      _corners[i] = Shape.reflectHorizontally(_corners[i], xReflectionPlane);
  }

  public void reflectVertically (int yReflectionPlane)
  {
    for (int i = 0; i < _corners.length; i++)
      _corners[i] = Shape.reflectVertically(_corners[i], yReflectionPlane);
  }


  public java.awt.Rectangle boundingBox()
  {
    int minX, minY, maxX, maxY;
    minX = maxX = _corners[0].x;
    minY = maxY = _corners[0].y;

    for (int i = 1; i < _corners.length; ++i)
    {
      java.awt.Point p = _corners[i];
      minX = (p.x < minX) ? p.x : minX;
      minY = (p.y < minY) ? p.y : minY;
      maxX = (maxX < p.x) ? p.x : maxX;
      maxY = (maxY < p.y) ? p.y : maxY;
    }

    return new java.awt.Rectangle(minX, minY, maxX-minX, maxY-minY);
  }


  public String toString()
  {
    String s =  "Polygon: ";
    for (int i = 0; i < _corners.length; ++i) {
      java.awt.Point p = _corners[i];
      s = s + "(" + p.x + "," + p.y + ") ";
    }
    return s;
  }


  public Object clone() 
  {
    Polygon copy = new Polygon(_corners.length);
    for (int i = 0; i < _corners.length; ++i) {
      copy.set_corner(i, _corners[i]);
    }
	return copy;
  }


    private java.awt.Point _corners[];

};

