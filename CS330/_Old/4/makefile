#
#  The command
#     make
#  will actually compile and build your program.
#  (The first time you use this Makefile in any directory, you may get
#  a warning about a missing "make.dep" file.  Ignore this message. This 
#  missing file will be generated for you.)
#
#  The command
#     make clean
#  will "clean up" your directory by deleting the executable program and
#  the various by-products of the compilation process.  Your source code
#  will be left unharmed.
#
#
########################################################################
# Macro definitions for "standard" C and C++ compilations
#
# Edit the next 5 definitions. After that, "make" will
#   build the program.
# 
#  Define special compilation flags for C++ compilation. These may change when
#  we're done testing and debugging.
CPPFLAGS=-g
#
#  Define special compilation flags for C compilation. These may change when
#  we're done testing and debugging.
CFLAGS=-g
# 
#  What is the name of the program you want to create?  (See below for notes
#     on using this makefile to generate multiple programs.)
TARGET=testpicture.exe
#
#  List the object code files to be produced by compilation. Normally this 
#  list will include one ".o" file for each C++ file (with names ending in 
#  ".cpp", ".cc" or ".C"), and/or each C file (with names ending in ".c").
#  Do NOT list .h files. For example, if you are building a program from 
#  source files foo.c, foo.h, bar.cpp, baz.cc, and bam.h you would use
#      OBJS=foo.o bar.o baz.o
#
# OR you can use the following line, which compiles all .c and .cpp files
# in the directory.
OBJS:=$(patsubst %.cpp,%.o,$(wildcard *.cpp)) $(patsubst %.c,%.o,$(wildcard *.c))
# 
#  What program should be used to link this program? If the program is
#  even partly C++, use g++.  If it is entirely C, use gcc.
LINK=g++ $(CPPFLAGS)
#LINK=gcc $(CFLAGS)
# 
#  Define special linkage flags.  Usually, these are used to include
#  special libraries of code, e.g., -lm to add the library of mathematical
#  routines such as sqrt, sin, cos, etc.
LFLAGS=-lm

#
#
#
#  In most cases, you should not change anything below this line.
#
#  The following is "boilerplate" to set up the standard compilation
#  commands:
#
.SUFFIXES:
.SUFFIXES: .d .o .h .c .cc .C .cpp
.c.o: ; $(CC) $(CFLAGS) -MMD -c $*.c
.cc.o: ; $(CPP) $(CPPFLAGS) -MMD -c $*.cc 
.C.o: ; $(CPP) $(CPPFLAGS) -MMD -c $*.C
.cpp.o: ; $(CPP) $(CPPFLAGS) -MMD -c $*.cpp

CC=gcc
CPP=g++

%.d: %.c
	touch $@
%.d: %.cc
	touch $@
%.d: %.C
	touch $@
%.d: %.cpp
	touch $@

DEPENDENCIES = $(OBJS:.o=.d)

# 
# Targets:
# 
all: $(TARGET)

$(TARGET): $(OBJS)
	$(LINK) $(FLAGS) -o $(TARGET) $(OBJS) $(LFLAGS)

clean:
	-rm -f $(TARGET) $(OBJS) $(DEPENDENCIES) make.dep


make.dep: $(DEPENDENCIES)
	-cat $(DEPENDENCIES) > make.dep

include make.dep


celllistenerseq.h: elementseq.h
	sed -e 's/Element/CellListenerPtr/g' -e 's/DEF_ELEMENT/class CellListener;/' < elementseq.h | sed -e 's/CellListenerPtr /CellListener* /g' > celllistenerseq.h

celllistenerseq.cpp: elementseq.cpp celllistenerseq.h
	sed -e 's/Element/CellListenerPtr/g' < elementseq.cpp | sed -e 's/CellListenerPtr /CellListener* /g' -e "s/elementseq/celllistenerseq/"> celllistenerseq.cpp

cellptrseq.h: elementseq.h
	sed -e 's/Element/CellPtr/g' -e 's/DEF_ELEMENT/class Cell;/' < elementseq.h | sed -e 's/CellPtr /Cell* /g' > cellptrseq.h

cellptrseq.cpp: elementseq.cpp cellptrseq.h
	sed -e 's/Element/CellPtr/g' < elementseq.cpp | sed -e 's/CellPtr /Cell* /g' -e "s/elementseq/cellptrseq/"> cellptrseq.cpp


cellnameseq.h: elementseq.h
	sed -e 's/Element/CellName/g' -e 's/DEF_ELEMENT/#include "cellname.h"/' < elementseq.h | sed -e 's/CellPtr /Cell* /g' > cellnameseq.h

cellnameseq.cpp: elementseq.cpp cellnameseq.h
	sed -e 's/Element/CellName/g' < elementseq.cpp | sed -e 's/CellPtr /Cell* /g' -e "s/elementseq/cellnameseq/"> cellnameseq.cpp



########################################################################
#
#  Advanced notes:  managing multiple programs with one makefile
#
#    If you want to create 2 or more programs (usually in the same directory)
#    using a single makefile, do the following:
#
#      1) For each program that you want to build, make distinct copies of
#         the TARGET= and OBJS= lines, adding a number to distinguish each.
#         For example:
#              TARGET1=myprogram1
#              TARGET2=myprogram2
#              OBJS1=mymain1.o foo.o bar.o
#              OBJS2=mymain2.o foo.o baz.o
#      2) Redefine TARGET and OBJS as the combination of the TARGETi and 
#         OBJSi lists.  For example,
#              TARGET=$(TARGET1) $(TARGET2)
#              OBJS=$(OBJS1) $(OBJS2)
#      3) In the "Dependencies:" area near the end of the file, find
#         the lines
#           $(TARGET): $(OBJS)
#       	$(LINK) $(FLAGS) -o $(TARGET) $(OBJS) $(LFLAGS)
#         and, as in step 1, make distinct, numbered copies for each program
#         you want to build.  For example,
#           $(TARGET1): $(OBJS1)
#       	$(LINK) $(FLAGS) -o $(TARGET1) $(OBJS1) $(LFLAGS)
#           $(TARGET2): $(OBJS2)
#       	$(LINK) $(FLAGS) -o $(TARGET2) $(OBJS2) $(LFLAGS)
#         These copies replace the original 2 lines.
#
########################################################################
#  Changes: 
#     6/4/96  Fixes to allow use under both "standard" Sun "make" and under
#             the GNU make.
#             Added notes on building multiple targets.
