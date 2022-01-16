import javax.swing.*;
import java.awt.*;
import java.awt.event.*;


/**
 *  An applet that launches the spreadsheet as a separate window.
 *
 */
public class LifeApplet extends JApplet
{

    Life window;

    public void init()
    {
	window = new Life(true);
	window.setTitle("CS330 Asst 6");
	window.pack();
    }

    public void start()
    {	
	window.show();
    }

    public void stop()
    {
	window.hide();
    }
    
    public void destroy()
    {
    }
}
