#include "split.h"

/** Utility function for parsing strings in which a number 
    of separate fields are separated by a number of delimiter strings;

    int n = split(theString, delimiter, fieldsArray, maxFields);

    scans theString for occurences of delimiter. The substrings of theString
    before the first occurence of the delimiter, in between two successive 
    delimiters, and after the last occurrence of delimiter are filled into
    fieldsArray. No more than maxFields values will be copied into the array. 
    The return value is the number of field values placed in the array.

    E.g.,

       string fields[4];
       int k = split("12/25/2004", "/", fields, 4);

    will result in fields[0]=="12", fields[1]=="25", fields[2]="2004",
    and k==3;
*/

using namespace std;

int split (const string& theString, const string& delimiter,
	   string* fields, int maxFields)
{
  string::size_type start = 0;
  int fieldCount = 0;

  int delimiterLength = delimiter.length();
  if (delimiterLength == 0)
	delimiterLength = 1;

  while (start < theString.length() && fieldCount < maxFields)
    {
      string::size_type end = theString.find(delimiter, start);
      if (end == string::npos) 
	end = theString.length();
      fields[fieldCount] = theString.substr(start, end-start);
      ++fieldCount;
      start = end + delimiter.length();
    }

  return fieldCount;
}




/**
  Same as above, except that an array is allocated just large enough to
  hold the fields. It is the callers responsibility to delete the array
  it is no longer needed.
*/

int split (const std::string& theString, const std::string& delimiter,
	   std::string*& fields)
{
  string::size_type start = 0;
  int fieldCount = 0;

  int delimiterLength = delimiter.length();
  if (delimiterLength == 0)
	delimiterLength = 1;

  while (start < theString.length())
    {
      string::size_type end = theString.find(delimiter, start);
      if (end == string::npos) 
	end = theString.length();
      ++fieldCount;
      start = end + delimiter.length();
    }

  fields = new string[fieldCount];
  return split(theString, delimiter, fields, fieldCount);
}

