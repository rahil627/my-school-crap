import java.awt.*;
import java.awt.event.*;
import javax.swing.*;


public class Life extends JFrame
{

  private LifeView viewer;
  private Choice initialPic;
  private boolean inApplet;
  
  public Life(boolean inAnApplet)
  {
      inApplet = inAnApplet;
      getContentPane().setLayout (new BorderLayout());

      initialPic.addItemListener (new ItemListener() {
	      public void itemStateChanged (ItemEvent e)
	      {
		  if (e.getStateChange() == ItemEvent.SELECTED) {
		      String value = initialPic.getSelectedItem();
		      viewer.reset (value);
		  }
	      }});
      
      
      JButton reset = new JButton("reset");
      reset.addActionListener (new ActionListener() {
	      public void actionPerformed (ActionEvent e) {
		  String value = initialPic.getSelectedItem();
		  viewer.setMaxGenerations(0);
		  viewer.reset (value);
	      }
	  } );
      
      JButton nextGen = new JButton("+");
      nextGen.addActionListener (new ActionListener() {
	      public void actionPerformed (ActionEvent e) {
		  viewer.setMaxGenerations(viewer.getMaxGenerations() + 1);
	      }
	  } );

      JButton zoomIn = new JButton("in");
      zoomIn.addActionListener (new ActionListener() {
	      public void actionPerformed (ActionEvent e) {
		  int size = viewer.getModelSize();
		  if (size > 1)
		      size = size / 2;
		  viewer.setModelSize(size);
		  String value = initialPic.getSelectedItem();
		  viewer.reset (value);
	      }
	  } );
      
      JButton zoomOut = new JButton("out");
      zoomOut.addActionListener (new ActionListener() {
	      public void actionPerformed (ActionEvent e) {
		  int size = viewer.getModelSize();
		  viewer.setModelSize(2*size);
		  String value = initialPic.getSelectedItem();
		  viewer.reset (value);
	      }
	  } );

      
      
      viewer = new LifeView();
      getContentPane().add (viewer, BorderLayout.CENTER);
      
      addWindowListener
	  (new WindowAdapter() {
		  public void windowClosing(WindowEvent e) {
		      if (inApplet)
			  hide();
		      else
			  System.exit(0);
		  }
	      });
  }
    

    

    public static void main(String args[]) {
	Life window = new Life(false);
	window.setTitle("CS330 Asst 6");
	window.pack();
	window.show();
    }

}