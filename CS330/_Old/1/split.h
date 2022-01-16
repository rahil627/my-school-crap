#ifndef SPLIT_H
#define SPLIT_H


#include <string>

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

int split (const std::string& theString, const std::string& delimiter,
	   std::string* fields, int maxFields);

/**
  Same as above, except that an array is allocated just large enough to
  hold the fields. It is the callers responsibility to delete the array
  it is no longer needed.
*/

int split (const std::string& theString, const std::string& delimiter,
	   std::string*& fields);


#endif
