package PipeLayer;

import PipeLayer.GameState;
import PipeLayer.Grid;
import PipeLayer.Pipe;
import PipeLayer.Scored;
import PipeLayer.StatusLine;
import javax.swing.*;
import javax.swing.event.*;
import java.awt.event.*;
import java.awt.*;


/**
 * The gameboard
 */
public class GameBoard extends JPanel
  implements Scored,
             ActionListener
{
  public StatusLine status;
  public Grid grid;

  public GameBoard()
  {
    setLayout (new BorderLayout());
    status = new StatusLine(this);
    add ("North", status);
    grid = new Grid();
    add ("Center", grid);
    add ("South", new JLabel("CS 330 - Fall 2008"));
    GameState gs = new GameState(status.nextPipeTile, this);
  }

    
    
  /**
   * Computes the current score and makes sure all pipes connected
   * to the starting position are marked as filled with water.
   */
  public synchronized void rescore()
  {
    int score = 0;
    int i = 4;
    int j = 0;
    Tile t = grid.getTile(i,j);
    Pipe p = t.getPipe();
    int dir = Pipe.Left;

    // Trace, from the starting position, though all connected pipes.
    while (p != null && p.isOpenAtThisEnd(dir))
      {
	++score;
	if (!p.isFilled())
	  {
	    p.fill();
	    t.repaint();
	  }
	int d = p.emergeDirection(dir);
	if (d == Pipe.Up)
	  {
	    --i;
	    if (i < 0)
	      p = null;
	  }
	else if	(d == Pipe.Down)
	  {
	    ++i;
	    if (i >= Grid.gridSize)
	      p = null;
	  }
	else if	(d == Pipe.Right)
	  {
	    ++j;
	    if (j >= Grid.gridSize)
	      p = null;
	  }
	else //	(d == Pipe.Left)
	  {
	    --j;
	    if (j < 0)
	      p = null;
	  }
	if (p != null)
	  {
	    t = grid.getTile (i, j);
	    p = t.getPipe();
	  }
	dir = (d + 2) % 4;
      }

    // Compute penalty for unfilled pipe lying around on the board.
    for (i = 0; i < Grid.gridSize; ++i)
      for (j = 0; j < Grid.gridSize; ++j)
	{
	  p = grid.getTile(i,j).getPipe();
	  if (p != null && !p.isFilled())
	    --score;
	}
    status.setScore (score);
    status.repaint();
  }

  public void computeScore()
  {
    rescore();
  }

  public void actionPerformed (ActionEvent e)
  {
    if (e.getActionCommand() == "reset")
      {
	grid.reset();
	computeScore();
	repaint();
      }
  }


}
