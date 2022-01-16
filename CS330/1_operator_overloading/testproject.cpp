#include <algorithm>
#include <iostream>
#include <sstream>
#include <string>

#include "unittest.h"
#include "project.h"
#include "division.h"

using namespace std;

void testDefaultConstructor()
{
  Project p;
  
  assertEqual ("", p.getTitle());
  assertEqual (NULL, p.getDivision());
  assertEqual ("", p.getProjectID());
  assertEqual ("", p.getBudgetCode());
  assertEqual (0, p.numberOfStaff());
  assertTrue (p == Project());
}

void test_setTitle()
{
  Project p;
  p.setTitle ("foo");

  assertEqual ("foo", p.getTitle());
}

void test_setDivision()
{
  Project p;
  Division *div = new Division();
  p.setDivision (div);

  assertEqual (div, p.getDivision());
  delete div;
}

void test_setProjectID()
{
  Project p;
  p.setProjectID ("foo");

  assertEqual ("foo", p.getProjectID());
}


void test_setBudgetCode()
{
  Project p;
  p.setBudgetCode ("foo");

  assertEqual ("foo", p.getBudgetCode());
}

void test_addStaff()
{
  Project p;
  Staff staff[] = {Staff("Rahil", "Patel"), Staff("Jason", "Jaring"), 
		   Staff("Smith", "S")};

  assertEqual (0, p.numberOfStaff());
  assertEqual (p.begin(), p.end());


  p.addStaff(staff[0]);
  assertEqual (1, p.numberOfStaff());
  assertEqual (staff[0], *(p.begin()));
  assertNotEqual (p.begin(), p.end());

  p.addStaff(staff[1]);
  assertEqual (2, p.numberOfStaff());
  assertTrue (equal(p.begin(), p.end(), staff));

  p.addStaff(staff[2]);
  assertEqual (3, p.numberOfStaff());
  assertTrue (equal(p.begin(), p.end(), staff));
}

void test_addStaff_order()
{
  Project p;
  Staff staff[] = {Staff("Smith", "S"), Staff("Rahil", "Patel"), 
		   Staff("Jason", "Jaring")
		   };

  Staff orderedStaff[] = {Staff("Rahil", "Patel"), Staff("Jason", "Jaring"), 
			  Staff("Smith", "S")};

  assertEqual (0, p.numberOfStaff());
  assertEqual (p.begin(), p.end());


  p.addStaff(staff[0]);
  assertEqual (1, p.numberOfStaff());
  assertEqual (staff[0], *(p.begin()));
  assertNotEqual (p.begin(), p.end());

  p.addStaff(staff[1]);
  assertEqual (2, p.numberOfStaff());
  Project::iterator it = p.begin(); 
  Staff s1 = *it;
  ++it;
  Staff s2 = *it;
  assertTrue (s1 < s2);

  p.addStaff(staff[2]);
  assertEqual (3, p.numberOfStaff());
  assertTrue (equal(p.begin(), p.end(), orderedStaff));
}

void testAssignment ()
{
  Project p0;

  Project p1, p2, p3;
  p1 = p0;

  assertEqual (p1, p0);

  
  Staff staff[] = {Staff("Rahil", "Patel"), Staff("Jason", "Jaring"), 
		   Staff("Ivar", "Sarreal")};
  p1.addStaff(staff[0]);
  p1.setTitle("a");
  p1.setDivision (new Division());
  p1.setBudgetCode ("b");
  p1.setProjectID("1");
  assertNotEqual (p0, p1);

  p2 = p1;

  assertEqual (p2, p1);

  p2.addStaff (staff[1]);
  p2.setProjectID("2");
  assertNotEqual (p2, p1);

  p3 = p2;
  assertEqual (p3, p2);
  p3.addStaff (staff[2]);
  p2.setProjectID("3");
  assertNotEqual (p3, p2);
}

void testCopyConstructor ()
{
  Project p0;

  Project p1;
  assertEqual (p1, p0);

  Staff staff[] = {Staff("Rahil", "Patel"), Staff("Jason", "Jaring"), 
		   Staff("Ivar", "Sarreal")};
  p1.addStaff(staff[0]);
  p1.setTitle("a");
  p1.setDivision (new Division());
  p1.setBudgetCode ("b");
  p1.setProjectID("1");
  assertNotEqual (p0, p1);

  Project p2 (p1);

  assertEqual (p2, p1);

  p2.addStaff (staff[1]);
  p1.setProjectID("2");
  assertNotEqual (p2, p1);
}

void testEquals ()
{
  Project p0;
  
  Project p1;

  assertEqual (p1, p0);

  
  p1.setProjectID("1");
  assertNotEqual (p1, p0);
  p0.setProjectID("1");
  assertEqual (p1, p0);
}

bool conditionsAreSane (const Project& p1, const Project& p2)
{
  int c = 0;
  if (p1 == p2) ++c;
  if (p1 < p2) ++c;
  if (p2 < p1) ++c;
  return (c == 1);
}

void testLess ()
{
  Project p0;
  
  Project p1;

  assertEqual (p1, p0);
  assertTrue (conditionsAreSane(p0, p1));
  
  Staff staff[] = {Staff("Rahil", "Patel"), Staff("Jason", "Jaring"), 
		   Staff("Ivar", "Sarreal")};

  for (int i = 0; i < 3; ++i) 
    {
      p1.addStaff(staff[i]);
      assertTrue (conditionsAreSane(p0, p1));
      p0.addStaff(staff[i]);
      assertTrue (conditionsAreSane(p0, p1));
    }

  p1.setTitle("abc");
  assertTrue (conditionsAreSane(p0, p1));
  p0.setTitle("abc");
  assertTrue (conditionsAreSane(p0, p1));

  p0.setProjectID("abc");
  p1.setProjectID("def");
  assertTrue (p0 < p1);
  assertTrue (conditionsAreSane(p0, p1));
  p0.setProjectID("def");
  assertFalse (p0 < p1);
  assertTrue (conditionsAreSane(p0, p1));


  p1.setDivision (new Division());
  assertTrue (conditionsAreSane(p0, p1));
  p0.setDivision (p1.getDivision());
  assertTrue (conditionsAreSane(p0, p1));


  p1.setBudgetCode ("xyz");
  assertTrue (conditionsAreSane(p0, p1));
  p0.setBudgetCode ("xyz");
  assertTrue (conditionsAreSane(p0, p1));
}

void testOutput ()
{
}

void runSuite()
{
  testDefaultConstructor();
  test_setTitle();
  test_setDivision();
  test_setProjectID();
  test_setBudgetCode();
  test_addStaff();
  test_addStaff_order();
  testCopyConstructor();
  testAssignment();
  testEquals ();
  testLess ();
  testOutput ();
}

int main ()
{
  runSuite();
  UnitTest::report(cout);
  cout << "All tests completed" << endl;
  return 0;
}
