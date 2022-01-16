package Pictures;

import java.awt.Color;
import java.awt.Graphics;

class Composite extends Shape
{
    public Composite(Color c)
    {
	super(c);
	firstShape = null;
	lastShape = null;
    }
       
   public Composite()
    {
	super(Color.black);
	firstShape = null;
	lastShape = null;
    }

   public Composite(Shape[] shapes)
    {
	super(Color.black);
	firstShape = null;
	lastShape = null;
	for (int i = 0; i < shapes.length; ++i)
	    addComponent((Shape)shapes[i].clone());
    }

   public Composite(Shape[] shapes, Color c)
    {
	super(c);
	firstShape = null;
	lastShape = null;
	for (int i = 0; i < shapes.length; ++i)
	    addComponent(shapes[i]);
    }


   public void addComponent (Shape s)
    {
	ShapeListNode newNode = new ShapeListNode();
	newNode.shape = (Shape)s.clone();
	newNode.next = null;
	if (lastShape == null) {
	    firstShape = newNode;
	} else {
	    lastShape.next = newNode;
	}
	lastShape = newNode;
    }

   public void move(int x, int y)
    {
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    p.shape.move(x,y);
    }

   public void plot(Graphics g)
    {
	g.setColor(getColor());
	java.awt.Rectangle bb = boundingBox();
	g.fillRect (bb.x, bb.y, bb.width, bb.height);
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    p.shape.plot(g);
    }

   public void reflectHorizontally (int xReflectionPlane)
    {
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    p.shape.reflectHorizontally(xReflectionPlane);
    }

   public void reflectVertically (int yReflectionPlane)
    {
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    p.shape.reflectVertically(yReflectionPlane);
    }

   public java.awt.Rectangle boundingBox()
    {
	if (firstShape == null)
	    return new java.awt.Rectangle();
	else {
	    java.awt.Rectangle bb = firstShape.shape.boundingBox();
	    for (ShapeListNode p = firstShape.next; p != null; p = p.next)
		bb = bb.union (p.shape.boundingBox());
	    return bb;
	}
    }

   public String toString()
    {
	String s = "Composite[ ";
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    s = s + p.shape.toString() + "; ";
	return s + "]";
    }

   public Object clone() 
    {
        Composite copy = new Composite(getColor());
	for (ShapeListNode p = firstShape; p != null; p = p.next)
	    copy.addComponent(p.shape);
	return copy;
    }


  private class ShapeListNode {
    Shape shape;
    ShapeListNode next;
  };

  private ShapeListNode firstShape;
  private ShapeListNode lastShape;

};
