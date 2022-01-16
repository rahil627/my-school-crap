package PipeLayer;

import PipeLayer.GameState;
import PipeLayer.Grid;
import PipeLayer.Pipe;
import PipeLayer.Scored;
import PipeLayer.StatusLine;
import javax.swing.*;
import javax.swing.event.*;


/**
 * The gameboard
 */
public class GameApplet extends JApplet
{
  public GameBoard board;
  
  public GameApplet()
  {
  }
  


  public void init ()
  {
    board = new GameBoard();
    getContentPane().add (board);
  }

    
    
  public void start() {}



}
