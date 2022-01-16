package PipeLayer;

import PipeLayer.GameState;
import PipeLayer.Tile;
import java.awt.*;

/**
 * The tile in the status line, used to indicate the next pipe.
 */
public class StatusTile extends Tile
{
  
  public StatusTile()
  {
    super();
  }


  public void paint (Graphics g)
  {
    Pipe p = GameState.nextPipe();
    if (p != getPipe())
      setPipe (p);
    super.paint(g);
  }


  protected void selected()
  {
  }
  

}
