//Represents a pipe that may be filled or empty, and may
//connect some combinations of the directions Up, Down, Left, and Right.

package PipeLayer;

public class PipeUD extends Pipe implements Cloneable
{
	private boolean filled;//Is liquid flowing through the pipe?

	//Symbolic names for directions.
	public final static int Up = 0;
	public final static int Left = 1;
	public final static int Down = 2;
	public final static int Right = 3;

	//constructor - initialize to empty
	public PipeUD()
	{
		filled = false;
	}

	//param direction  Which end are we asking about?
	//return True if liquid can enter or leave a pipe from the indicated direction.
	public boolean isOpenAtThisEnd (int direction)
	{
		return (direction == Up) || (direction == Down);
	}

	//From what direction will the liquid emerge?
	//param entryDirection The direction from which liquid is entering the pipe.
	//return The direction at which the liquid will emerge.
	public int emergeDirection (int entryDirection)
	//pre: isOpenAtThisEnd(entryDirection)
	{
		if (entryDirection == Up)return Down;
		else return Up;
	}
  
  	//Is there liquid flowing through the pipe?
	public boolean isFilled()
	{
		return filled;
	}


	//Indicate that liquid is now in the pipe.
	public void fill()
	{
		filled = true;
	}
  
	//Indicate that no liquid is now in the pipe.
	 public void empty()
	 {
		 filled = false;
	 }
  
	 //Make a copy of the current pipe.
	 public PipeUD copy() throws CloneNotSupportedException
	 {
	   return (PipeUD)clone();
	 }
}