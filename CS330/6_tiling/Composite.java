/*
In this assignment you will implement part a system for drawing pictures composed of a collection of basic shapes. 

You will be looking at the process of working with inheritance and dynamic binding from both the point of view of the application writer and the class developer.

The application consists of an inheritance hierarchy with base class Shape, a handful of subclasses of Shape, a Picture class that serves as a container of Shapes, and a main program test driver to exercise the Picture class.

In most figure drawing programs, it is possible to form a new shape object by selecting a group of existing shapes and "grouping" them to form a single Composite shape. That composite object can then be moved, scaled, reflected, etc., as if it were a single entity.

Your task is to complete the implementation of a Composite class in this Shape hierarchy.
*/

package Pictures;

import java.awt.Color;
import java.awt.Graphics;

class Composite extends Shape
{
	//private
	private class ShapeListNode//OLD SCHOOL!
	{
		Shape shape;
		ShapeListNode next;//class inside it's own class?
		ShapeListNode prev;
	};
	private ShapeListNode firstShape;
	private ShapeListNode lastShape;
	private FilledRectangle backgroundColor;
	
	//public
	Composite(Color c)//used in main
    {
		super(c);//putColor(c);
		firstShape = null;
		lastShape = null;
    	backgroundColor = new FilledRectangle(new java.awt.Point(), new java.awt.Point(), c);
    }
    public void addComponent (Shape s)
    {
    	ShapeListNode newNode = new ShapeListNode();
    	newNode.shape = (Shape)s.clone();
    	newNode.next = null;
    	newNode.prev = lastShape;
    	
    	if (lastShape == null) {firstShape = newNode;} 
    	else {lastShape.next = newNode;}
    	lastShape = newNode;	
    }
	public void plot(Graphics g)
	{
		/*
		//plot the background color first
		java.awt.Point p1 = new java.awt.Point(boundingBox().x,boundingBox().y);//top left
		java.awt.Point p2 = new java.awt.Point(boundingBox().x+boundingBox().width,boundingBox().y+boundingBox().height);//bottom right
		backgroundColor= new FilledRectangle(p1, p2, getColor());
		backgroundColor.plot(g);
		*/
		backgroundColor = new FilledRectangle(new java.awt.Point(boundingBox().x,boundingBox().y), new java.awt.Point(boundingBox().x+boundingBox().width,boundingBox().y+boundingBox().height) , getColor());
		backgroundColor.plot(g);
		
		//using graphics
		//g.setColor(getColor());
		//g.fillRect(boundingBox().x, boundingBox().y, boundingBox().width, boundingBox().height);
		
		//then the shapes
		for (ShapeListNode p = firstShape; p != null; p = p.next)p.shape.plot(g);
	}
	public void move                (int x, int y)			{for (ShapeListNode p = firstShape; p != null; p = p.next)p.shape.move(x, y);}
    public void reflectHorizontally (int xReflectionPlane)	{for (ShapeListNode p = firstShape; p != null; p = p.next)p.shape.reflectHorizontally(xReflectionPlane);}
	public void reflectVertically   (int yReflectionPlane)	{for (ShapeListNode p = firstShape; p != null; p = p.next)p.shape.reflectVertically(yReflectionPlane);}
	public java.awt.Rectangle boundingBox()//used in reflecting test
	{
		/*
		//this will return the union of all the shapes' BBs
		ShapeListNode p = new ShapeListNode();//iterater
		java.awt.Rectangle temp = new java.awt.Rectangle(0,0,0,0);//this hurt my head
		for (p = firstShape; p != null; p = p.next)
		{
			//temp = p.shape.boundingBox();
			//if(temp.x+temp.width>largest.x+largest.width){largest.width=temp.x+temp.width;}
			//if(temp.y+temp.height>largest.y+largest.height){largest.height=temp.y+temp.height;}
			
			temp = temp.union(p.shape.boundingBox());
		}
		return temp;
		*/
		
		//this will return the union of all the shapes' BBs
		ShapeListNode p = firstShape;//iterater
		java.awt.Rectangle temp = p.shape.boundingBox();//BB
		for (p = firstShape.next; p != null; p = p.next)
		{
			//temp = p.shape.boundingBox();
			//if(temp.x+temp.width>largest.x+largest.width){largest.width=temp.x+temp.width;}
			//if(temp.y+temp.height>largest.y+largest.height){largest.height=temp.y+temp.height;}
			
			temp = temp.union(p.shape.boundingBox());
		}
		return temp;
	}
	public String toString()
	{
		String c="";
		//String temp;
		for (ShapeListNode p = firstShape; p != null; p = p.next)
		{
			//temp=p.shape.toString();
			//c = c+temp;//concatenate all the shape.toString()'s together
			
			c += p.shape.toString();//L33T shortcut!
		}
		
		return c;
    }
    public Object clone()
    {
        Composite c = new Composite(getColor());
		for (ShapeListNode p = firstShape; p != null; p = p.next)c.addComponent((Shape)p.shape.clone());
        return c;

    }
}
