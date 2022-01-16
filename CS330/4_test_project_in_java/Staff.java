/**
 * Staff members in the company
 */
public class Staff implements Comparable<Staff>, Cloneable {
	public Staff ()
	{
		theSurname = "";
		theGivenName = "";
	}

	public Staff (String surname, String givenName)
	{
		theSurname = surname;
		theGivenName = givenName;
	}

	public String getSurname()             {return theSurname;}
	public void putSurname(String surname) {theSurname = surname;}

	public String getGivenName()               {return theGivenName;}
	public void putGivenName(String givenName) {theGivenName = givenName;}


	public boolean equals (Object o)
	{
		Staff r = (Staff)o;
		return theSurname.equals(r.theSurname) 
		&& theGivenName.equals(r.theGivenName);
	}
	
	public int hashCode()
	{
		return 3 * theSurname.hashCode() + theGivenName.hashCode();
	}

	public int compareTo (Staff r)
	{	
		return toString().compareTo(r.toString());
	}

	public String toString()
	{
		return theSurname + ", " + theGivenName;
	}
	
	public Object clone()
	{
		return new Staff(theSurname, theGivenName);
	}

	private String theSurname;
	private String theGivenName;
}

