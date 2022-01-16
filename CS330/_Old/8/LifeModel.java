import java.util.Hashtable;

public class LifeModel {

    private int theSize;
    private Hashtable cells;


    public LifeModel(int initialSize)
    {
	setSize (initialSize);
	reset();
    }


    public int getSize() { return theSize; }

    public void setSize(int newSize)
    {
	theSize = newSize;
	cells = new Hashtable();
    }


    public int getCell(int x, int y) 
    {
	Integer age = (Integer) cells.get(key(x,y));
	if (age == null)
	    return 0;
	else
	    return age.intValue();
    }


    public void reset ()  {reset(0.5);}

    public void reset (int patternNumber)
    {// special patterns for testing
	cells = new Hashtable();
    if (patternNumber == 1) { // R-pentamino
      int m = theSize / 2;
      cells.put(key(m+1,m+0), new Integer(1));
      cells.put(key(m+2, m+0), new Integer(1));
      cells.put(key(m+0, m+1), new Integer(1));
      cells.put(key(m+1, m+1), new Integer(1));
      cells.put(key(m+1, m+2), new Integer(1));
    } else if (patternNumber == 2) { // Box
      for (int x = 0; x < theSize; ++x) {
        cells.put (key(x,0), new Integer(1));
        cells.put (key(x,theSize-1), new Integer(1));
        cells.put (key(0,x), new Integer(1));
        cells.put (key(theSize-1,x), new Integer(1));
      }
    } else // X Box
      for (int x = 0; x < theSize; ++x) {
        cells.put (key(x,0), new Integer(1));
        cells.put (key(x,theSize-1), new Integer(1));
        cells.put (key(0,x), new Integer(1));
        cells.put (key(theSize-1,x), new Integer(1));
        cells.put (key(x,x), new Integer(1));
        cells.put (key(x,theSize-1-x), new Integer(1));
      }
    }



    public void reset (double density)
    {
	cells = new Hashtable();
    for (int x = 0; x < theSize; ++x)
      for (int y = 0; y < theSize; ++y)
        if (Math.random() <= density) {
          cells.put (key(x,y), new Integer(1));
        }
    }


    public void nextGeneration ()
    {
	Hashtable newCells = new Hashtable();
	for (int x = 0; x < theSize; ++x)
	    for (int y = 0; y < theSize; ++y) {
		int oldValue = getCell(x,y);
		int sum = 0;
		for(int dx = -1; dx <= 1; ++dx)
		    for(int dy = -1; dy <= 1; ++dy)
			if (dx != 0 || dy != 0)
			    if (getCell(x+dx,y+dy) != 0)
				++sum;
		if (oldValue == 0) {
		    if (sum == 3)
			newCells.put(key(x,y), new Integer(1));
		} else {
		    if (sum == 2 || sum == 3)
			newCells.put(key(x,y), new Integer(oldValue+1));
		}
	    }
	cells = newCells;
    }


    private Integer key(int x, int y)
    {
	int k = -1;
	if (x >= 0 && y >= 0 && x < theSize && y < theSize) {
	    k = x * theSize + y;
	}
	return new Integer(k);
    }

}
