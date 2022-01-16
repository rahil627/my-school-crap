package PipeLayer;

import PipeLayer.BoardLayout;
import PipeLayer.Pipe;
import PipeLayer.Tile;
import javax.swing.*;
import java.awt.*;

/**
 * A grid of tiles
 */
public class Grid extends JPanel
{
  public static final int gridSize = 8;

  private Tile[] tiles = new Tile[gridSize * gridSize];

  public Grid()
  {
    BoardLayout gl = new BoardLayout(gridSize, gridSize);
    setLayout (gl);
    for (int i = 0; i < gridSize; ++i)
      for (int j = 0; j < gridSize; ++j)
	{
	  add (tiles[i+j*gridSize] = new Tile());
	}
    tiles[4].setPipe(new Pipe());
  }


  public void reset()
  {
    for (int i = 0; i < gridSize; ++i)
      for (int j = 0; j < gridSize; ++j)
	{
	  tiles[i+j*gridSize].setPipe (null);
	}
    tiles[4].setPipe(new Pipe());
  }


  public Tile getTile(int i, int j)
  {
    return tiles[i+j*gridSize];
  }


  public void paint (Graphics g)
  {
    g.setColor (Color.black);
    /*    for (int i = 0; i < gridSize; ++i)
      for (int j = 0; j < gridSize; ++j)
	{
	  getTile(i,j).paint(g);
	}
    */
    g.drawRect (0, 0,
		8*tiles[0].getSize().width,
		8*tiles[0].getSize().height);
  }
}
