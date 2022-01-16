import java.applet.Applet;
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;

/**
    A text input box, with an associated label, for entering integer values
*/
public class IntegerInput extends JPanel {

    /** input area for an integer value */
    private JTextField intIn;

    /**
       Create a labelled integer input box
       @param label          Label to appear alongside the input area
       @param initialValue   initial value for this input area
       @param width          minimum number of characters for input area
    */
    public IntegerInput (String label, int initialValue, int width)
    {
      setBackground(Color.lightGray);
      add (new Label(label));
      intIn = new JTextField("" + initialValue, width);
      add (intIn);
    }

  /**
     Get the current value of the input.
     @return Integer equivalent of the text in the box, or 0 if the
     text in the box is not a valid integer
  */
  public int getInt()
  {
	int ivalue = 0;
	try {
      Integer i = new Integer(intIn.getText());
      ivalue = i.intValue();
	} catch (Exception ex) {}
	return ivalue;
  }

    /**
       Set the value of the input box, displaying the new value.
       @param i new value for the box
    */
    public void setValue (int i)
    {
	intIn.setText("" + i);
	intIn.repaint();
    }

    /**
       Add a function to be invoked whenever Return is typed in
       the input area.
       @param l listener function to add
    */
    public void addActionListener (ActionListener l) {
	intIn.addActionListener (l);
    }

    /**
       Remove a function that would have been invoked whenever Return
       is typed in the input area.
       @param l listener function to remove
    */
    public void removeActionListener (ActionListener l) {
	intIn.removeActionListener (l);
    }
}
