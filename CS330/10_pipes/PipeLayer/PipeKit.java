package PipeLayer;

//A collection of one each of all the available kinds of pipes.
import PipeLayer.Pipe;
import PipeLayer.PipeUD;
import java.util.Vector;


public class PipeKit
{
  private Vector thePipes;//container of pipes
  
  public PipeKit()
  {
    // Initialize the vector
    thePipes = new Vector();

    // Add pipes
    thePipes.addElement (new Pipe());
    thePipes.addElement (new PipeUD());
    thePipes.addElement (new PipeLU());
  }
  
  public int size()//# of pipes
  {
    return thePipes.size();
  }

  public Pipe getPipe (int index)//Select a pipe from the kit.
  //pre: index >= 0 && index < size()
  {
    return (Pipe)(thePipes.elementAt(index));
  }

}
