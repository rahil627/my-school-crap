import java.applet.Applet;
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;


import java.util.LinkedList;
import java.util.ListIterator;


/**
 An applet/application drawing a hypocycloid,
 (the mathematical generalization of a "spirograph")
 */

public class HypoCycloid extends JApplet 
{
  
  // The Model
  
  /** the color used for the first dots drawn (shades gradually into 
   <code>color2</code> as more dots are printed */
  private Color color1 = Color.red;
  
  /** the color used for the last dots drawn (shades gradually from 
   <code>color1</code> as more dots are printed */
  private Color color2 = Color.blue;
  
  /** radius of the outer circle */
  private int outerRadius = 105;
  /** radius ofthe inner circle */
  private int innerRadius = 22;
  /** # of revolutions to keep */
  private int nRevolutions = 10;
  
  /** The number of points plotted per revolution. */
  private int nPoints = 100; 


  private JApplet self;
  
  private class PlottedPoint {
    double x;
    double y;
    Color color;
    
    public PlottedPoint (double xx, double yy, Color col)
    {
      x = xx; y = yy; color = col;
    }
    
    public String toString()
    {
      return "(" + x + "," + y + "): " + color;
    }
  }
  
  
  private LinkedList points;
  
  // The View & Controls
  
  /** the graphics area in which the fratal shape is drawn */
  private JPanel canvas;
  
  
  /** button to bring up color selection dialog for <code>color1</code>
   @see #color1
   */
  private Button colorChooser1;
  
  /** button to bring up color selection dialog for <code>color2</code> 
   @see #color2
   */
  private Button colorChooser2;
  
  /** Labeled integer input area for parameter a */
  private IntegerInput outerRadiusIn;
  /** Labeled integer input area for parameter b */
  private IntegerInput innerRadiusIn;
  /** Labeled integer input area for parameter c */
  private IntegerInput nRevolutionsIn;
  
  /** Labeled integer input area for fractal parameter nDots
   @see #nDots
   */
  private IntegerInput nPointsIn;
  
  
  
  /**
   Create the applet
   */
  public HypoCycloid() 
  {
    points = new LinkedList();
    self = this;
  }
  
  /** 
   Build the GUI components for this applet.
   */
  public void init() {
    // set up the components
    Container content = getContentPane();

    content.setLayout (new BorderLayout());
    
    canvas = new JPanel () {
      
      public void paint (Graphics g) {
        super.paint(g);
        paintCanvas(g);
      }
    };
    canvas.setBackground(Color.white);
    canvas.setPreferredSize(new Dimension(400,400));
    content.add (canvas, BorderLayout.CENTER);
    
    
    JPanel controls = new JPanel();
    
    colorChooser1 = new Button("Color 1");
    colorChooser1.setForeground(color1);
    controls.add (colorChooser1);
    colorChooser1.addActionListener 
      (new ActionListener() {
          public void actionPerformed(ActionEvent e) {
            try {
              color1 = JColorChooser.showDialog(self, "Color 1", color1); 
            } catch (Exception ex) {}
            colorChooser1.setForeground(color1);
            reset();
          }
        }
       );


    
    colorChooser2 = new Button("Color 2");
    colorChooser2.setForeground(color2);
    controls.add (colorChooser2);
    colorChooser2.addActionListener 
      (new ActionListener() {
          public void actionPerformed(ActionEvent e) {
            try {
              color2 = JColorChooser.showDialog(self, "Color 2", color2); 
            } catch (Exception ex) {}
            colorChooser2.setForeground(color2);
            reset();
          }
        }
       );
    
    outerRadiusIn = new IntegerInput ("outer radius:", outerRadius, 3);
    outerRadiusIn.addActionListener (new ActionListener()
                             {
      public void actionPerformed(ActionEvent e) {
        try {
          outerRadius = outerRadiusIn.getInt();
          reset();
        } catch (Exception ex) {};
      }
    });
    controls.add(outerRadiusIn);
    
    innerRadiusIn = new IntegerInput ("inner radius:", innerRadius, 3);
    innerRadiusIn.addActionListener (new ActionListener()
      {
        public void actionPerformed(ActionEvent e) {
          try {
            innerRadius = innerRadiusIn.getInt();
            reset();
          } catch (Exception ex) {};
        }
      });
    controls.add(innerRadiusIn);
    
    nRevolutionsIn = new IntegerInput ("# rev:", nRevolutions, 3);
    nRevolutionsIn.addActionListener (new ActionListener() {
      public void actionPerformed(ActionEvent e) {
        try {
          nRevolutions = nRevolutionsIn.getInt();
          reset();
        } catch (Exception ex) {};
      }
    });
    controls.add(nRevolutionsIn);
    
    
    nPointsIn = new IntegerInput ("# pts/rev:", nPoints, 3);
    nPointsIn.addActionListener (new ActionListener()
                                 {
      public void actionPerformed(ActionEvent e) {
        try {
          nPoints = nPointsIn.getInt();
          reset();
        } catch (Exception ex) {};
      }
    });
    controls.add(nPointsIn);
    
    
    
    content.add (controls, BorderLayout.SOUTH);
    validate();
  }
  
  
  /**
   Start the applet when the web page is loaded
   */
  public void start() 
  {
	Thread thisThread = Thread.currentThread();
	computer c = new computer();
	c.start();

    while (true) 
    {
        try{thisThread.sleep(1000);}
        catch (InterruptedException e){}

        canvas.repaint();
    }
    
  }
  
  /**
   Invoked when the web page is exited
   */
  public void stop() {
  }
  
  /**
   Invoked when the applet is no longer needed
   */
  public void destroy() {
  }
  
  
  
  /**
   Interpolate between two extremal integer values
   @param x value to return when i==0
   @param y value to reutrn when i==steps-1
   @param steps number of steps to take between x and y
   @param i desired step number
   @return a value that is i/(steps-1) between x and y
   */
  private
    int interpolate (int x, int y, int i, int steps)
  {
    return (i * y + (steps-i)*x) / steps;
  }
  
  
/**
   Interpolate between two extremal integer values
   @param x value to return when i==0
   @param y value to reutrn when i==1
   @param steps number of steps to take between x and y
   @param i desired interpolation level (0.0 - 1.0)
   @return a value between x and y
   */
  private
    int interpolate (int x, int y, double i)
  {
    return (int)(i * (double)y + (1.0-i)*(double)x);
  }
  
  /**
   Interpolate between two extremal color values
   @param c1 value to return when i==0
   @param c2 value to reutrn when i==steps-1
   @param steps number of steps to take between c1 and c2
   @param i desired step number
   @return a value that is i/(steps-1) between c1 and c2
   */
  private
    Color interpolate(Color c1, Color c2, int i, int steps)
  {
    return new Color (interpolate(c1.getRed(), c2.getRed(), i, steps),
                      interpolate(c1.getGreen(), c2.getGreen(), i, steps),
                      interpolate(c1.getBlue(), c2.getBlue(), i, steps));
  }
  
 /**
   Interpolate between two extremal color values
   @param c1 value to return when i==0
   @param c2 value to reutrn when i==1
   @param i desired interpolation level
   @return a value that is i/(steps-1) between c1 and c2
   */
  private
    Color interpolate(Color c1, Color c2, double i)
  {
    return new Color (interpolate(c1.getRed(), c2.getRed(), i),
                      interpolate(c1.getGreen(), c2.getGreen(), i),
                      interpolate(c1.getBlue(), c2.getBlue(), i));
  } 
  
  /**
   Reset the drawing state, discarding previously computed points
   */
  private void reset ()
  {
    points.clear();
  }


  /**
   Compute one point of the cycloid, saving it into the points list
   @param t parametric angle of point to be drawn
   */
  class computer extends Thread //(double t)
  {
	  double t=0.0;
	  int i=0;
	  public void run()
	  {
		//while(true)
		//{
		    if (points.size() >= nRevolutions * nPoints)
		      points.removeFirst();
		    
		    double aNorm = 1.0;
		    double bNorm = (double)innerRadius / (double)outerRadius;
		    
		    double cosT = Math.cos(t);
		    double c1x = (aNorm - bNorm) * cosT;
		    double c1y = (aNorm - bNorm) * Math.sin(t);
		    double t2 = -t / bNorm;
		    double x = c1x + bNorm * Math.cos(t2);
		    double y = c1y + bNorm * Math.sin(t2);
		    
		    Color c = interpolate(color1, color2, Math.acos(cosT)/2.0/Math.PI); 
		    points.add(new PlottedPoint(x, y, c));
		    t = t + 2.0 * Math.PI / (double)nPoints;
		    if (t > 1000.0 * Math.PI)
		      t = 0.0;
		    ++i;
		    if (i >= nPoints) i = 0;
		//}
	  }
  }
  /**
   Run as a standalone application, opening a window cotaining
   the fractal applet (as a panel).
   @param args ignored
   */
  static public void main (String args[]) 
  {
    HypoCycloid panel = new HypoCycloid();
    panel.init();
    
    JFrame theWindow = new JFrame();
    theWindow.getContentPane().add (panel);
    theWindow.addWindowListener
      (new WindowAdapter() {
      public void windowClosing(WindowEvent e) {
        System.exit(0);
      }
    });
    theWindow.setTitle ("CS330, Spring 2006, Asst 7");
    theWindow.setSize(400,450);
    theWindow.pack();
    theWindow.show();
    panel.start();//starts here
  }
  
  
  

  private  void paintCanvas (Graphics g) 
  {
    int w = canvas.getSize().width;
    int h = canvas.getSize().height;
    double r = (w < h)? (double)w : (double)h;
    r = r / 2.0;
        
    ListIterator p = points.listIterator();
    PlottedPoint prev = null;
    while (p.hasNext()) { 
      PlottedPoint current = (PlottedPoint)p.next();
      if (prev != null) {
        int x1 = (int)(r + prev.x *  r);
        int y1 = (int)(r + prev.y *  r);
        int x2 = (int)(r + current.x *  r);
        int y2 = (int)(r + current.y *  r);
        g.setColor (current.color);
        g.drawLine(x1, y1, x2, y2);
      }
      prev = current;
    }
 }
}

