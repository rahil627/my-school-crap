package PipeLayer;

import PipeLayer.Pipe;
import PipeLayer.PipeKit;
import PipeLayer.Scored;

import java.util.Random;
import java.awt.Component;


class GameState 
{
  private static PipeKit kit = new PipeKit();
  private static Pipe theNextPipe = null;

  private static Random rnd = new Random();
  private static Component nextPipeGraphic;
  private static Scored game;
  
  
  public GameState(Component nextPipeTile, Scored theGame)
  {
    nextPipeGraphic = nextPipeTile;
    game = theGame;
  }


  public static Pipe nextPipe()
  {
    if (theNextPipe == null)
      useNextPipe();
    return theNextPipe;
  }

  public static void rescore()
  {
    if (game != null)
      game.computeScore();
  }
  
  public static Pipe useNextPipe()
  {
    Pipe p = theNextPipe;
    theNextPipe = kit.getPipe (Math.abs(rnd.nextInt()) % kit.size());
    try
      {
	theNextPipe = theNextPipe.copy();
      }
    catch (CloneNotSupportedException exc)
      {
	theNextPipe = new Pipe();
      }
    nextPipeGraphic.repaint();
    return p;
  }
}

    
