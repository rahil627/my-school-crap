package PipeLayer;

import PipeLayer.GameState;
import PipeLayer.Pipe;
import java.awt.*;
import java.awt.event.*;

/**
 * A single tile on the gameboard, possibly showing a picture of a pipe.
 */
public class Tile extends Canvas
{
  /**
   * The pipe to be shown.
   */
  private Pipe thePipe;
    

  private static final int tileSize = 30;
  
  public Tile()
  {
    thePipe = null;
    /*
    addMouseListener
      (new MouseAdapter () 
       {
	 public void MousePressed (MouseEvent e)
	   {
	     selected();
	   }
       });
    */
  }


  public boolean mouseDown (Event e, int x, int y)
  {
    selected();
    return true;
  }
  
  public synchronized void setPipe (Pipe p)
  {
    thePipe = p;
    repaint();
  }
  
  public synchronized Pipe getPipe ()
  {
    return thePipe;
  }

  public Dimension getPreferredSize ()
  {
    return new Dimension(tileSize, tileSize);
  }

  public Dimension getMinimumSize ()
  {
    return getPreferredSize();
  }

  public Dimension getMaximumSize ()
  {
    return getPreferredSize();
  }
  
  /**
   *  Draws the tile.
   */
  public void paint (Graphics g)
  {
    if (thePipe == null) {
      // No pipe - just draw an empty box.
      g.setColor (Color.black);
      g.drawRect (0, 0, tileSize, tileSize);
    }
    else {
      final int pipeWidth = tileSize / 5;
      final int pipeWidthX2 = 2*pipeWidth;
      final int pipeWidthX3 = 3*pipeWidth;

      // Draw pipe outlines: From each open end, draw a pipe segment to
      //  the middle of the tile.

      // First, draw black outlines of the pipe segments.
      g.setColor (Color.black);
      if (thePipe.isOpenAtThisEnd(Pipe.Up))
	{
	  g.drawLine (pipeWidthX2, 0, pipeWidthX2, pipeWidthX3);
	  g.drawLine (pipeWidthX2, pipeWidthX3, pipeWidthX3, pipeWidthX3);
	  g.drawLine (pipeWidthX3, pipeWidthX3, pipeWidthX3, 0);
	}
      if (thePipe.isOpenAtThisEnd(Pipe.Down))
	{
	  g.drawLine (pipeWidthX2, tileSize, pipeWidthX2, pipeWidthX2);
	  g.drawLine (pipeWidthX2, pipeWidthX2, pipeWidthX3, pipeWidthX2);
	  g.drawLine (pipeWidthX3, pipeWidthX2, pipeWidthX3, tileSize);
	}
      if (thePipe.isOpenAtThisEnd(Pipe.Left))
	{
	  g.drawLine (0, pipeWidthX2, pipeWidthX3, pipeWidthX2);
	  g.drawLine (pipeWidthX3, pipeWidthX2, pipeWidthX3, pipeWidthX3);
	  g.drawLine (pipeWidthX3, pipeWidthX3, 0, pipeWidthX3);
	}
      if (thePipe.isOpenAtThisEnd(Pipe.Right))
	{
	  g.drawLine (tileSize, pipeWidthX2, pipeWidthX2, pipeWidthX2);
	  g.drawLine (pipeWidthX2, pipeWidthX2, pipeWidthX2, pipeWidthX3);
	  g.drawLine (pipeWidthX2, pipeWidthX3, tileSize, pipeWidthX3);
	}

      // Then, fill in the pipe with the appropriate color
      g.setColor ((thePipe.isFilled())? Color.green : Color.gray);
      if (thePipe.isOpenAtThisEnd(Pipe.Up))
	{
	  g.fillRect (pipeWidthX2+1, 1, pipeWidth-1, pipeWidthX3-1);
	}
      if (thePipe.isOpenAtThisEnd(Pipe.Down))
	{
	  g.fillRect (pipeWidthX2+1, pipeWidthX2+1,
		      pipeWidth-1, pipeWidthX3-1);
	}
      if (thePipe.isOpenAtThisEnd(Pipe.Left))
	{
	  g.fillRect (1, pipeWidthX2+1,
		      pipeWidthX3-1, pipeWidth-1);
	}
      if (thePipe.isOpenAtThisEnd(Pipe.Right))
	{
	  g.fillRect (pipeWidthX2+1, pipeWidthX2+1,
		      pipeWidthX3-1, pipeWidth-1);
	}
    }
  }
  


  protected void selected()
  {
    if (thePipe == null)
      {
	thePipe = GameState.useNextPipe();
	repaint();
	GameState.rescore();
      }
  }
  

}
