import junit.framework.*;
import junit.textui.TestRunner;


import java.util.Iterator;


/**
 * Test of the Project class
 */
public class TestProject extends TestCase{
		  
	public TestProject (String testName) {
		super(testName);
	}

	public static void main( String[] args ) throws Exception {
		TestRunner.run( suite() );
	}
		  
	public static Test suite() {
		return new TestSuite(TestProject.class);
	}
	
	public void test_DefaultConstructor()
	{
	  Project p = new Project();
	  assertTrue (true);
	  // insert appropriate tests on p
	}


    // insert additional tests

}
