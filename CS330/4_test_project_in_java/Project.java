import java.io.BufferedReader;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Collections;

public class Project implements Comparable<Project>{
//Insert your code here
	
	//variables
	private String theTitle;
	private String theProjectID;
	private String theBudgetCode;
	private ArrayList<Division> theDivision;

	private int numStaff;
	private int theMaxStaff;
	private ArrayList<Staff> staff;

	private static int DEFAULT_MAXSTAFF = 12;
	
	public Iterator<Staff> iterator(){return staff.iterator();}
	
	public Project(){//(maxStaff)?
		theTitle=new String();
		theProjectID=new String();
		theBudgetCode=new String();
		theDivision = new ArrayList<Division>();
		numStaff=0;
	    theMaxStaff=DEFAULT_MAXSTAFF;
		staff = new ArrayList<Staff>();
	}
	
	  //~Project();
	  
	  //Project (const Project&);//?

	  // Attributes
	  public String getTitle() {return theTitle;}
	  public void putTitle(String title) {theTitle = title;}
	  
	  public Division getDivision() {return theDivision.get(numStaff);}
	  public void putDivision(ArrayList<Division> publ)  {theDivision = publ;}

	  public String getProjectID() {return theProjectID;}
	  public void putProjectID(String projectID) {theProjectID = projectID;}

	  // Budget codes indicate to whom this project is charged.
	  public String getBudgetCode() {return theBudgetCode;}
	  public void putBudgetCode(String projectID) {theBudgetCode = projectID;}
	  
	  public int numberOfStaff(){return numStaff;}
	  
	  public void addStaff(Staff mystaff)//alphabetically, operators for strings needed
	  {
	    assert (numStaff < theMaxStaff);
	    //assert (proj.getClass().getName().contains("Project"));
	    staff.add(mystaff);
	    //have to sort?
	    /*int k = numStaff;
	    while (k > 0 && mystaff < staff[k-1])
	      {
	        staff[k] = staff[k-1];
	        k--;
	      }
	    staff[k] = mystaff;*/
	    ++numStaff;
	  }
	  public static Project read(BufferedReader in, ArrayList<Division> dlist) throws java.io.IOException
	  {
			String line = in.readLine();
			if (line == null)
				return null;
			String fields[] = line.split("\t");
			//if (fields.length != 4)
				//throw new java.io.IOException ("Improper input format for Division");
			  Project p = new Project();
			  p.putTitle(fields[0]);
			  p.putProjectID(fields[1]);
			  p.putBudgetCode(fields[2]);
			  p.putDivision(dlist);//this is way off =P
			  //staff, check the .dat file
			  return p;
	  }
	  public int compareTo(Project p) throws ClassCastException
	  {
		  	if (!(p instanceof Project))
			throw new ClassCastException("A Project object expected.");
			int result = theTitle.compareTo (p.theTitle);
			if (result == 0)
				result = theProjectID.compareTo(p.theProjectID);
			if (result == 0)
				result = theBudgetCode.compareTo(p.theBudgetCode);
			if (result == 0)
			{
				int pNumStaff = ((Project) p).numberOfStaff();
				result = this.numStaff - pNumStaff;
			}
			//compare division and staff are in those classes...so should be simple...
			return result;   
		}
		//public boolean equals(Object d) {
	    //public int hashCode()
		public String toString()
		{
			StringBuffer buf = new StringBuffer();
			buf.append (theTitle);
			buf.append (":");
			buf.append (theProjectID);
			buf.append (":");
			buf.append (theBudgetCode);
			buf.append (":");
			//buf.append (numStaff);
			//buf.append (": ");
			//get DivisionCode?
			Iterator<Staff> it = staff.iterator();
			while (it.hasNext()) {
				Staff s = it.next();
				buf.append (s.toString());//?
				buf.append(", ");
			}
			return buf.toString();
		}
}
