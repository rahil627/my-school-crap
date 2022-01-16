#ifndef DEBUG_H
#define DEBUG_H

#include <iostream>


/**
  Declares a pair of macros for debugging output

  If the symbol DEBUG is defined, then
     CERR("message");
  prints "Debug: message" on standard output, and
     DBG (data);
  prints something on the order of
     debug@foo.cpp:23 data=...value-of-data
  on standard output.

  In both cases (but, most usefully, with CERR), the expression can 
  be extended with << operators:
     CERR ("message" << data);


  If the symbol DEBUG is not defined, then these two macros do nothing.
*/

#ifdef DEBUG
#define DBG(x) cout.flush(); std::cerr << " debug@" << __FILE__ << ":" << __LINE__ << " " << #x << " == " << x << std::endl
#define CERR(x) std::cout << std::flush; std::cerr << " debug " << x << std::endl;
#else
#define DBG(x)
#define CERR(x)
#endif


#endif
