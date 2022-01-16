package PipeLayer;

import java.awt.Component;
import java.awt.Container;
import java.awt.Dimension;
import java.awt.Insets;
import java.awt.LayoutManager;
import java.util.Vector;

public class BoardLayout implements LayoutManager {

  private int minWidth = 0, minHeight = 0;
  private int preferredWidth = 0, preferredHeight = 0;
  private boolean sizeUnknown = true;

  private int height;
  private int width;
  
  public BoardLayout(int w, int h)
  {
    height = h;
    width = w;
  }

  public void addLayoutComponent(String name, Component comp) {
  }

  /* Required by LayoutManager. */
  public void removeLayoutComponent(Component comp) {
  }

  private void setSizes(Container parent)
  {
    int nComps = parent.getComponentCount();
    Dimension d = null;
    
    //Reset preferred/minimum width and height.
    preferredWidth = 1;
    preferredHeight = 1;
    minWidth = 1;
    minHeight = 1;

    for (int i = 0; i < nComps; i++)
      {
	Component c = parent.getComponent(i);
	if (c.isVisible())
	  {
	    d = c.getPreferredSize();
	    
	    preferredWidth += d.width;
	    preferredHeight += d.height;
	    
	    minWidth += d.width;
	    minHeight += d.height;
	  }
      }
  }


  /* Required by LayoutManager. */
  public Dimension preferredLayoutSize(Container parent)
  {
    Dimension dim = new Dimension(0, 0);

    setSizes(parent);
    
    //Always add the container's insets!
    Insets insets = parent.getInsets();
    dim.width = preferredWidth + insets.left + insets.right;
    dim.height = preferredHeight + insets.top + insets.bottom;
    
    sizeUnknown = false;

    return dim;
  }

  /* Required by LayoutManager. */
  public Dimension minimumLayoutSize(Container parent) {
    Dimension dim = new Dimension(0, 0);
    int nComps = parent.getComponentCount();
    
    //Always add the container's insets!
    Insets insets = parent.getInsets();
    dim.width = minWidth + insets.left + insets.right;
    dim.height = minHeight + insets.top + insets.bottom;
    
    sizeUnknown = false;
    
    return dim;
  }


  /* Required by LayoutManager. */
  /* This is called when the panel is first displayed, 
   * and every time its size changes. 
   * Note: You CAN'T assume preferredLayoutSize() or minimumLayoutSize()
   * will be called -- in the case of applets, at least, they probably
   * won't be. */
  public void layoutContainer(Container parent) {
    Insets insets = parent.getInsets();
    int maxWidth = parent.getSize().width
      - (insets.left + insets.right);
    int maxHeight = parent.getSize().height
      - (insets.top + insets.bottom);
    
    int nComps = parent.getComponentCount();
	
    int previousWidth = 0, previousHeight = 0;
    int x = insets.left;
    int y = insets.top;

    if (sizeUnknown) {
      setSizes(parent);
    }

    Dimension d = new Dimension();

    for (int i = 0; i < nComps; ++i)
      {
	Component c = parent.getComponent(i);
	d = c.getPreferredSize();

	if (i % width == 0)
	    {
	      x = insets.left;
	      if (i > 0)
		y += d.height;
	    }

	c.setBounds (x, y, d.width, d.height);
	x += d.width;
      }
    }


    
    public String toString() {
        return getClass().getName() + "[width=" + width +
	  "  height=" + height +"]";
    }
}
