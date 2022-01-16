#include <iostream>
#include <iomanip>

#include "unittest.h"

using namespace std;

long UnitTest::numSuccesses = 0L;
long UnitTest::numFailures = 0L;
long UnitTest::DETAIL_LIMIT = 64L;
long UnitTest::NOTICE_INTERVAL = 10L;

void UnitTest::checkTest (bool condition, const char* conditionStr,
			  const char* fileName, int lineNumber)
{
  if (condition)
    {
      ++numSuccesses;
    }
  else
    {
      ++numFailures;
      if (numFailures < DETAIL_LIMIT)
	{
	  cerr << "UnitTest: " << "Failed test #" 
	       << numFailures + numSuccesses << ": "
	       << conditionStr << ", in line "
	       << lineNumber << " of " << fileName << endl;
	}
    }
  long total = numFailures + numSuccesses;
  if (total % NOTICE_INTERVAL == 0)
    cerr << "UnitTest: completed " << total << " tests so far" << endl;
  if (total == NOTICE_INTERVAL * 10L)
    NOTICE_INTERVAL = 10L * NOTICE_INTERVAL;
}

void UnitTest::checkTest (bool condition, const string& conditionStr,
			  const char* fileName, int lineNumber)
{
  checkTest(condition, conditionStr.c_str(), fileName, lineNumber);
}



// Print a simple summary report
void UnitTest::report (std::ostream& out)
{
  out << "UnitTest: passed " << numSuccesses << " out of " 
      << getNumTests() << " tests, for a success rate of "
      << std::showpoint << std::fixed << std::setprecision(1)
      << (100.0 * numSuccesses)/(float)getNumTests() 
      << "%" << endl;
}
