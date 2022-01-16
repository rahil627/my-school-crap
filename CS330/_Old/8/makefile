JAVAPATH=/research/java/jdk1.5.0_05/bin/
#CLASSPATH=.:/research/java/j2sdk1.4.2/lib/classes.zip
JOPTIONS=-g
USERHOME=$(HOME)

.SUFFIXES:
.SUFFIXES: .class .java
#.java.class: export CLASSPATH; CLASSPATH=$(CLASSPATH); $(JAVAPATH)/javac -nowarn $(JOPTIONS) $*.java
.java.class: $(JAVAPATH)javac -nowarn $(JOPTIONS) $*.java

#
# As you define new classes, add them to the following list.
# It may not be absolutely necessary, but it will help guarantee that
# necessary recompilation gets done.
#
#CLASSES:=foo.class bar.class
# or use the following to compile all .java files
CLASSES:=$(patsubst %.java,%.class,$(wildcard *.java))
TOPPACKAGE=

TARGET=Life
ASSIGNMENT=CS330_S07_Asst7


all: $(ASSIGNMENT).jar


%.class: %.java
	$(JAVAPATH)javac -nowarn $(JOPTIONS) $*.java

run: $(ASSIGNMENT).jar
	$(JAVAPATH)java  -cp $(ASSIGNMENT).jar $(TARGET)


runsample: 
	$(JAVAPATH)java -classpath '/home/zeil/cs330/Assignments/LifeThreads/$(ASSIGNMENT).jar'  $(TOPPACKAGE)$(TARGET)

jar: $(ASSIGNMENT).jar


$(ASSIGNMENT).jar: $(CLASSES)
	jar cf temp.jar *.class
#	mv temp.jar $(ASSIGNMENT).jar
	$(JAVAPATH)java -cp /home/zeil/src/retroguard-v2.2.0/retroguard.jar RetroGuard temp.jar $(ASSIGNMENT).jar retroguard.dat
	rm temp.jar 


applet: $(ASSIGNMENT).jar $(USERHOME)/public_html/$(ASSIGNMENT) index.html
	cp $(ASSIGNMENT).jar $(USERHOME)/public_html/$(ASSIGNMENT)
	chmod 644 $(USERHOME)/public_html/$(ASSIGNMENT)/$(ASSIGNMENT).jar
	cp index.html $(USERHOME)/public_html/$(ASSIGNMENT)/index.html
	chmod 644 $(USERHOME)/public_html/$(ASSIGNMENT)/index.html
	echo Use a web browser to visit http://www.cs.odu.edu/~$(USER)/$(ASSIGNMENT)/

$(USERHOME)/public_html:
	mkdir $(USERHOME)/public_html
	chmod o+rx $(USERHOME)/public_html

$(USERHOME)/public_html/$(ASSIGNMENT): $(USERHOME)/public_html
	mkdir $(USERHOME)/public_html/$(ASSIGNMENT)
	chmod o+rx $(USERHOME)/public_html/$(ASSIGNMENT)



clean:
	-rm -f *.class
	-rm -rf $(USERHOME)/public_html/$(ASSIGNMENT)




