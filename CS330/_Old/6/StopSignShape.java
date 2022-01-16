import java.awt.*;

import java.awt.geom.*;





public class StopSignShape extends SelectableShape

{

   /**

      Constructs a car shape.

      @param x the left of the bounding rectangle

      @param y the top of the bounding rectangle

      @param width the width of the bounding rectangle

   */

   public StopSignShape(int x, int y, int width)

   {

      this.x = x;

      this.y = y;

      this.width = width;

   }



   public void draw(Graphics2D g2)

   {
      //octogan points
      int[] xPoints = {0, 10, 20, 30, 30, 20, 10, 0};
      int[] yPoints = {70, 60, 60, 70, 80, 90, 90, 80};

      g2.drawPolygon(xPoints, yPoints, 8); //draw sign
      g2.drawLine(15, 150, 15, 90); //draw post

   }



   public boolean contains(Point2D p)

   {

      return x <= p.getX() && p.getX() <= x + width 

         && y <= p.getY() && p.getY() <= y + width / 2;

   }



   public void translate(int dx, int dy)

   {

      x += dx;

      y += dy;

   }



   private int x;

   private int y;

   private int width;

}

