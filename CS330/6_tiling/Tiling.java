package Pictures;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.StreamTokenizer;
import java.net.URL;

import javax.swing.JApplet;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JPanel;

//* Java 1.6 only
import javax.swing.filechooser.FileNameExtensionFilter;



/*--------------------------------------------------------------------------*
 * Input file format:
 *    1) color for composite (3 integers in range 0-255 for r g b values)
 *    2) followed by an arbitrary number of shape descriptions.
 *         Each shape description has the form:
 *          shapeKind color variant-data
 *         where
 *           A) shapeKind is a single character indicating the kind of shape
 *               C circle
 *               F filled rectangle
 *               L line segment
 *               P polygon
 *               R rectangle
 *           B) color is given in the same format as 1) above
 *           C) variant-data is one or more ints. Number and meaning 
 *              depends upon the shape kind
 *               C: center_x center_y radius
 *               F,L,R: upperleft_x upperleft_y lowerright_x lowerright_y
 *               P: number-of-vertices, x1, y1, x2, y2, ...
 *    
 */

public
class Tiling extends JApplet {

    /**
	 * 
	 */
	private static final long serialVersionUID = -3667679560015790648L;
	
	public java.util.Vector listOfShapes;
    public JPanel drawingArea;
    public Picture currentPicture;
    public Color compositeColor;

    public Tiling ()
    {
	currentPicture = null;
	listOfShapes = new java.util.Vector();
    }

    public void init () {
    	init(true);
    }
    
    public void init (boolean inAnApplet) {
 
      drawingArea = new JPanel() {
		private static final long serialVersionUID = 0L;

		public void paint(Graphics g) {
		    super.paint(g);
		    if (currentPicture != null)
              currentPicture.draw(g);
          }
	    };
      drawingArea.setMinimumSize(new Dimension(400,400));
      drawingArea.setPreferredSize(new Dimension(400,400));

      add(drawingArea);
      
      JMenuBar menuBar = new JMenuBar();
      setJMenuBar (menuBar);
      
      JMenu fileMenu = new JMenu("File");
      menuBar.add(fileMenu);
      
      JMenu viewMenu = new JMenu("View");
      menuBar.add(viewMenu);
      
      JMenuItem loadItem = new JMenuItem("Open");
      loadItem.addActionListener 
	  (new ActionListener()
	      {
		  public void actionPerformed (ActionEvent e) {
			  JFileChooser chooser = new JFileChooser();
			  String[] filterList = {"dat"}; 
			   //Java 1.6 only
		      FileNameExtensionFilter filter = new FileNameExtensionFilter("Shape Data Files", filterList);
		      chooser.setFileFilter(filter);
			  
		      int returnVal = chooser.showOpenDialog(null);
		      if(returnVal == JFileChooser.APPROVE_OPTION) {
		    	  readShapes(chooser.getSelectedFile().getName());
		      }
		  }
	      });
      fileMenu.add(loadItem);
      
      if (!inAnApplet) {
    	  JMenuItem quitItem = new JMenuItem("Quit");
    	  quitItem.addActionListener 
    	  (new ActionListener()
	      	{
    		  public void actionPerformed (ActionEvent e) {
    			  System.exit(0);
    		  }
	      	}
    	  );
    	  fileMenu.add (quitItem);
      }
	      
	      
      JMenuItem baseItem = new JMenuItem("Draw Shapes");
      baseItem.addActionListener
	  (new ActionListener()
	      {
		  public void actionPerformed (ActionEvent e) {
		      baseTest();
		  }
	      });
      viewMenu.add(baseItem);
      
      JMenuItem drawMenuItem = new JMenuItem("Draw Composite");
      drawMenuItem.addActionListener
	    (new ActionListener()
          {
		    public void actionPerformed (ActionEvent e) {
              drawTest();
		    }
          });
      viewMenu.add(drawMenuItem);
      
      JMenuItem moveMenuItem = new JMenuItem("Move Composite");
      moveMenuItem.addActionListener
	    (new ActionListener()
          {
		    public void actionPerformed (ActionEvent e) {
              moveTest();
		    }
          });
      viewMenu.add(moveMenuItem);
      
      JMenuItem reflectMenuItem = new JMenuItem("Reflect Composite");
      reflectMenuItem.addActionListener
	    (new ActionListener()
          {
		    public void actionPerformed (ActionEvent e) {
              reflectTest();
		    }
          });
      viewMenu.add(reflectMenuItem);
      
      JMenuItem tileMenuItem = new JMenuItem("Tiles");
      tileMenuItem.addActionListener
	    (new ActionListener()
          {
		    public void actionPerformed (ActionEvent e) {
              tileTest();
		    }
          });
      viewMenu.add(tileMenuItem);
      
      

     }
  
    static public void main (String args[]) 
    {
	JFrame window = new JFrame();
	Tiling contents = new Tiling();
	contents.init(false);
	window.add (contents);

	window.addWindowListener
	(new WindowAdapter() {
		public void windowClosing(WindowEvent e) {
		    System.exit(0);
	    }
		});
	 window.setTitle ("CS330, Spring 2008");
	 window.pack();
	 window.setVisible(true);
    }
				     

    public void drawTest ()
    {
	Picture pic = new Picture();
	Composite c = new Composite(compositeColor);

	for (int i = 0; i < listOfShapes.size(); ++i)
	    c.addComponent ((Shape)listOfShapes.elementAt(i));
	pic.add(c);
	currentPicture = pic;
	drawingArea.repaint();
    }


    public void moveTest ()
    {
	Picture pic = new Picture();
	Composite c = new Composite(compositeColor);

	for (int i = 0; i < listOfShapes.size(); ++i)
	    c.addComponent ((Shape)listOfShapes.elementAt(i));
	
	for (int i = 0; i < 5; ++i)
	    {
		int x = 50*i;
		int y =(int)(Math.sqrt(200.0*200.0 - (double)(x*x)));
		Shape s = (Shape)c.clone();
		s.move(x,y);
		pic.add (s);
	    }
	currentPicture = pic;
	drawingArea.repaint();
    }



    public void reflectTest ()
    {
	Picture pic = new Picture();
	Composite c = new Composite(compositeColor);

	for (int i = 0; i < listOfShapes.size(); ++i)
	    c.addComponent ((Shape)listOfShapes.elementAt(i));

	pic.add (c);

	java.awt.Rectangle bb = c.boundingBox();
	
	Shape s = (Shape)c.clone();
	s.reflectHorizontally(bb.x + bb.width * 2 / 3);

	Composite c2 = new Composite(Color.white);
	c2.addComponent (c);
	c2.addComponent (s);
	pic.add(c2);
	
	Shape t = (Shape)c2.clone();
	t.reflectVertically(bb.y + bb.height * 3 / 2);
	pic.add(t);
	
	currentPicture = pic;
	drawingArea.repaint();
    }
    



    private static int interpolate (int x, int y, int i, int steps)
    {
	return (i * x + (steps-i)*y) / steps;
    }


    private static Color interpolate(Color c1, Color c2, int i, int steps)
    {
	return new Color (interpolate(c1.getRed(), c2.getRed(), i, steps),
                      interpolate(c1.getGreen(), c2.getGreen(), i, steps),
                      interpolate(c1.getBlue(), c2.getBlue(), i, steps));
    }


    public void tileTest ()
    {
	Picture pic = new Picture();
	Composite comp = new Composite(compositeColor);

	for (int i = 0; i < listOfShapes.size(); ++i)
	    comp.addComponent ((Shape)listOfShapes.elementAt(i));

	Color c1 = comp.getColor();
	Color c2  = new Color(255-c1.getRed(),
			      255-c1.getGreen(),
			      255-c1.getBlue());
	
	java.awt.Rectangle bb = comp.boundingBox();
	comp.move(-bb.x, -bb.y);//reflect over x and reflect over y
	bb = comp.boundingBox();
	
	int width = bb.width;
	if (width < 0) width = 1;
	int height = bb.height;
	if (height < 0) height = 1;
	int nTiles = (width < height) ? 400/height : 400/width;
	if (nTiles < 2) nTiles = 2;
	
	for (int ky = 0; ky < nTiles; ++ky)
	    {
		for (int kx = 0; kx < nTiles; ++kx)
		    {
			Shape aTile = (Shape)comp.clone();
			aTile.putColor (interpolate(c1, c2, kx+ky,
						     2*nTiles));
			if ((kx % 2) == 1)
			    aTile.reflectHorizontally(width/2);
			if ((ky % 2) == 1)
			    aTile.reflectVertically(height/2);
			aTile.move (kx*width, ky*height);
			pic.add (aTile);
		    }
	    }

	currentPicture = pic;
	drawingArea.repaint();
    }
    

    public void baseTest()
    {
	Picture pic = new Picture();
	for (int i = 0; i < listOfShapes.size(); ++i)
	    pic.add ((Shape)listOfShapes.elementAt(i));
	currentPicture = pic;
	drawingArea.repaint();
    }


    static private int readInt(StreamTokenizer st) throws IOException
    {
	if (st.ttype != StreamTokenizer.TT_EOF) {
	    int ttype = st.nextToken();
	    while (ttype != StreamTokenizer.TT_NUMBER
		   && ttype != StreamTokenizer.TT_EOF)
		ttype = st.nextToken();
	}
	return (int)(st.nval + 0.5);
    }

    static private String readString(StreamTokenizer st) throws IOException
    {
	if (st.ttype != StreamTokenizer.TT_EOF) {
	    int ttype = st.nextToken();
	    while (ttype != StreamTokenizer.TT_WORD
		   && ttype != StreamTokenizer.TT_EOF)
		ttype = st.nextToken();
	}
	return st.sval;
    }

    public void readShapes (String dataFileName)
    {
	// Read shapes from input file
	// If the file name begins with "http:", then read via the web.
	// Otherwise, read from the local disk.

	int red, green, blue, x1, x2, y1, y2, r, n;
	listOfShapes = new java.util.Vector();
	try {
	    BufferedReader reader = null;

	    boolean isURL =  (dataFileName.indexOf("://") >= 0)
		|| (dataFileName.indexOf("file:") == 0);
	    
	    // First, read the source code
	    if (isURL)
		{
		    URL url = new URL(dataFileName);
		    reader = new BufferedReader
			(new InputStreamReader (url.openStream()));
		}
	    else
		reader = new BufferedReader (new FileReader (dataFileName));

	    StreamTokenizer shapesIn = new StreamTokenizer(reader);
	    shapesIn.parseNumbers();

	    red = readInt(shapesIn);
	    green = readInt(shapesIn);
	    blue = readInt(shapesIn);
	    
	    compositeColor = new Color (red, green, blue);

	    String shapeKind;
	    Shape s = null;

	    while (shapesIn.ttype != StreamTokenizer.TT_EOF) {
		shapeKind = readString(shapesIn);
		
		red = readInt(shapesIn);
		green = readInt(shapesIn);
		blue = readInt(shapesIn);
		Color color  = new Color(red, green, blue);
		
		switch (shapeKind.charAt(0)) {
		case 'C': // circle
		    x1 = readInt(shapesIn);
		    y1 = readInt(shapesIn);
		    r = readInt(shapesIn);
		    s = new Circle (new java.awt.Point(x1, y1), r, color);
		    break;
		    
		case 'R': // rectangle
		    x1 = readInt(shapesIn);
		    y1 = readInt(shapesIn);
		    x2 = readInt(shapesIn);
		    y2 = readInt(shapesIn);
		    s = new Rectangle (new java.awt.Point(x1, y1),
				       new java.awt.Point(x2, y2), color);
		    break;
		    
		case 'F': // filled rectangle
		    x1 = readInt(shapesIn);
		    y1 = readInt(shapesIn);
		    x2 = readInt(shapesIn);
		    y2 = readInt(shapesIn);
		    s = new FilledRectangle (new java.awt.Point(x1, y1),
					     new java.awt.Point(x2, y2),
					     color);
		    break;
		    
		case 'L': // line segment
		    x1 = readInt(shapesIn);
		    y1 = readInt(shapesIn);
		    x2 = readInt(shapesIn);
		    y2 = readInt(shapesIn);
		    s = new LineSegment (new java.awt.Point(x1, y1),
					 new java.awt.Point(x2, y2), color);
		    break;
		    
		case 'P': // polygon
		    n = readInt(shapesIn);
		    Polygon p = new Polygon (n, color);
		    for (int i = 0; i < n; ++i)
			{
			    x1 = readInt(shapesIn);
			    y1 = readInt(shapesIn);
			    p.set_corner (i, new java.awt.Point(x1,y1));
			}
		    s = p;
		    break;
		}
		listOfShapes.add (s);
		}
	} catch (Exception e) {}
    }
}
