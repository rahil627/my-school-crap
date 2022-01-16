package PipeLayer;

import PipeLayer.Pipe;
import PipeLayer.Tile;
import javax.swing.*;
import javax.swing.event.*;
import java.awt.event.*;
import java.awt.*;

  

/**
 * Status line for the game: contains the next piece of pipe to place,
 *  a timer bar, and the current score.
 */
public class StatusLine extends JPanel
{
  public StatusTile nextPipeTile;
  private JLabel scoreLabel;

  public JButton resetButton;
  //  public Button quitButton;
  

  public StatusLine(ActionListener resetRoutine)
  {
    setLayout (new FlowLayout());
    nextPipeTile = new StatusTile();
    add (nextPipeTile);

    scoreLabel = new JLabel ("score: 0");
    add (scoreLabel);

    resetButton = new JButton("Reset");
    add (resetButton);
    resetButton.setActionCommand("reset");
    resetButton.addActionListener (resetRoutine);
    
    /*
    quitButton = new Button("Quit");
    add (quitButton);

    if (inAnApplet) {
      quitButton.addActionListener
	(new ActionListener () {
	  public void actionPerformed(ActionEvent e)
	    {
	      destroy();
	    }
	});
    }
    else {
      quitButton.addActionListener
	(new ActionListener() {
	  public void actionPerformed(ActionEvent e)
	    {
	      System.exit(0);
	    }
	});
    }
    */
  }

  public void setScore (int score)
  {
    scoreLabel.setText ("score: " + score);
    repaint();
  }
  
  /*
  public void paint (Graphics g)
  {
    nextPipeTile.paint(g);
    scoreLabel.paint(g);
    quitButton.paint(g);
  }
  */
}
