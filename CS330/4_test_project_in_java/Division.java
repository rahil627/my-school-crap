import java.io.BufferedReader;
import java.util.ArrayList;
import java.util.Iterator;

/**
 * Describes a division of the company.
 */

public class Division implements Comparable<Division>{
	private String theName;
	private String theRoom;
	private String theBuilding;
	private String theDivisionCode;

	private ArrayList<Project> activeProjects;

	public Division() {
		theName = "";
		theRoom = "";
		theBuilding = "";
		theDivisionCode = "";
		activeProjects = new ArrayList<Project>();
	}

//	Attributes
	public String getName() {return theName;}
	public void putName(String name) {theName = name;}

	public String getRoom() {return theRoom;}
	public void putRoom(String room) {theRoom = room;}

	public String getBuilding() {return theBuilding;}
	public void putBuilding(String building) {theBuilding = building;}

	public String getDivisionCode() {return theDivisionCode;}
	public void putDivisionCode(String divisionCode) {theDivisionCode = divisionCode;}

	public void addProject(Project proj)
	{
		assert (proj.getClass().getName().contains("Project"));
		activeProjects.add(proj);
	}

	public int numberOfProjects()
	{
		return activeProjects.size();
	}

	public static Division read (BufferedReader in) throws java.io.IOException
	{
		String line = in.readLine();
		if (line == null)
			return null;
		String fields[] = line.split("\t");
		if (fields.length != 4)
			throw new java.io.IOException ("Improper input format for Division");
		  Division d = new Division();
		  d.putName (fields[0]);
		  d.putRoom (fields[1]);
		  d.putBuilding (fields[2]);
		  d.putDivisionCode (fields[3]);
		  return d;
	}

	public Iterator<Project> iterator()
	{
		return activeProjects.iterator();
	}

	public int compareTo(Division d) {
		int result = theName.compareTo (d.theName);
		if (result == 0)
			result = theDivisionCode.compareTo(d.theDivisionCode);
		if (result == 0)
			result = theBuilding.compareTo(d.theBuilding);
		if (result == 0)
			result = theRoom.compareTo(d.theRoom);
		return result;
	}

	public boolean equals(Object d) {
		Division div = (Division)d;
		return (compareTo(div) == 0);
	}
	
	public int hashCode()
	{
		int result = theName.hashCode();
		result = 3 * result + theDivisionCode.hashCode();
		result = 3 * result + theBuilding.hashCode();
		result = 3 * result + theRoom.hashCode();
		return result;
	}
	
	public String toString()
	{
		StringBuffer buf = new StringBuffer();
		buf.append (theName);
		buf.append (", ");
		buf.append (theDivisionCode);
		buf.append (", ");
		buf.append (theBuilding);
		buf.append (", ");
		buf.append (theRoom);
		buf.append (": ");
		Iterator<Project> it = activeProjects.iterator();
		while (it.hasNext()) {
			Project p = it.next();
			buf.append (p.getTitle());
			buf.append(", ");
		}
		return buf.toString();
	}

}



