package Pictures;

import java.awt.Color;
import java.awt.Graphics;

class Picture
{
    public Picture()
    {
	firstShape = null;
	lastShape = null;
    }
    
    public Picture(Shape[] shapes)
    {
	firstShape = null;
	lastShape = null;
	for (int i = 0; i < shapes.length; ++i)
	    add((Shape)shapes[i].clone());
    }
    
    
    public void clear ()
    {
	firstShape = null;
	lastShape = null;
    }
    
    public void add (Shape s)
    {
	ShapeListNode newNode = new ShapeListNode();
	newNode.shape = (Shape)s.clone();
	newNode.next = null;
	newNode.prev = lastShape;
	
	if (lastShape == null) {
	    firstShape = newNode;
	} else {
	    lastShape.next = newNode;
	}
	lastShape = newNode;
    }
    
    public void remove (Shape s)
    {
	ShapeListNode current = firstShape;
	while (current != null && current.shape != s)
	    current = current.next;
	if (current != null) {
	    if (current == firstShape && current == lastShape) {
		firstShape = lastShape = null;
	    } else if (current == firstShape) {
		firstShape = current.next;
		firstShape.prev = null;
	    } else if (current == lastShape) {
		lastShape = current.prev;
		lastShape.next = null;
	    } else {
		current.prev.next = current.next;
		current.next.prev = current.prev;
	    }
	}
    }

    public void moveTowardFront(Shape s)
    {
	ShapeListNode current = firstShape;
	while (current != null && current.shape != s)
	    current = current.next;
	if (current != null && current != lastShape) {
	    current.shape = current.next.shape;
	    current.next.shape = s;
	}
    }

    public void moveTowardBack(Shape s)
    {
	ShapeListNode current = firstShape;
	while (current != null && current.shape != s)
	    current = current.next;
	if (current != null && current != firstShape) {
	    current.shape = current.prev.shape;
	    current.prev.shape = s;
	}
    }



    public Shape find (java.awt.Point closeTo)
    {
	
	Shape found = null;
	for (ShapeListNode current = firstShape; current != null ;
	     current=current.next) {
	    if (current.shape.boundingBox().contains(closeTo))
		found = current.shape;
	}
	return found;
    }


    public java.util.Vector find (java.awt.Rectangle enclosedWithin)
    {
	java.util.Vector results = new java.util.Vector();
	for (ShapeListNode current = firstShape; current != null ;
	     current=current.next) {
	    if (enclosedWithin.contains(current.shape.boundingBox()))
		results.add(current.shape);
	}
	return results;
    }


   public void draw(Graphics g)
    {
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    p.shape.plot(g);
    }



   public String toString()
    {
	String s = "Picture { ";
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    s = s + p.shape.toString() + "; ";
	return s + "}";
    }



  private class ShapeListNode {
    Shape shape;
    ShapeListNode next;
    ShapeListNode prev;
  };

  private ShapeListNode firstShape;
  private ShapeListNode lastShape;

};

