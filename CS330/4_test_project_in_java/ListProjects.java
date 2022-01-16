import java.io.*;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Iterator;



//This program reads a set of division information from divisions.dat
//and a set of project information from projects.dat, then produces a report
//of projects issued by each division, sorted by division name, 
//projectID, and title.


public class ListProjects {

	static public void main(String[] argv)
	{
		listByDivision();
	}



	private static void readDivisions (ArrayList<Division> divisions)
	{
		try {
			BufferedReader divisionsIn = new BufferedReader
			(new FileReader("divisions.dat"));
			while (true) {
				Division d = Division.read(divisionsIn);
				if (d != null)
					divisions.add(d);
				else
					break;
			}
		} catch (java.io.IOException ex) {
			System.err.println (ex);
			System.exit(1);
		}
	}






	private static void readProjects 
	(ArrayList<Division> divisions, 
			ArrayList<Project> projects)
	{
		try {
			BufferedReader in = new BufferedReader
			(new FileReader("projects.dat"));
			while (true) {
				Project p = Project.read(in, divisions);
				if (p != null) {
					projects.add(p);
					p.getDivision().addProject(p);
				} else
					break;
			}
		} catch (java.io.IOException ex) {
			System.err.println (ex);
			System.exit(1);
		}
	}





	private static String padString (int length, String s)
	{
		// Clip s to the desired length or pad it with blanks on the right
		// to get it up to that length.
		StringBuffer buf = new StringBuffer(s);
		while (buf.length() < length)
			buf.append (' ');
		return buf.toString().substring(0, length);
	}



	private static void printReport (ArrayList<Division> divisionSet)
	{
		// List each division (in the order they occurred
		//   in the divisions.dat file)
		for (int k = 0; k < divisionSet.size(); ++k) {
			Division div = divisionSet.get(k);
			System.out.println (div.getName() + ": " + div.getDivisionCode());

			// For each division, 
			if (div.numberOfProjects() > 0) {
				// sort the projects produced by that division (sort by 
				//    project ID)
				ArrayList<Project> projects = new ArrayList<Project>();
				for (Iterator<Project> it = div.iterator(); it.hasNext(); ) {
					projects.add(it.next());
				}
				Collections.sort (projects);

				// and print them
				for (int bk = 0; bk < div.numberOfProjects(); ++bk)
				{
					Project b = projects.get(bk);
					System.out.print (padString(38, "    " +  b.getProjectID() + ' ' + b.getTitle()));
					System.out.print (' ');

					for (Iterator<Staff> a = b.iterator(); a.hasNext();)
					{
						Staff s = a.next();
						System.out.print (s);
						if (a.hasNext())
							System.out.print("; ");
					}
					System.out.println();
				}
			}
		}
	}




	private static void listByDivision()
	{
		ArrayList<Division> divisionSet = new ArrayList<Division>();
		readDivisions(divisionSet);

		ArrayList<Project> projectSet = new ArrayList<Project>();
		readProjects (divisionSet, projectSet);

		printReport (divisionSet);
	}
}

