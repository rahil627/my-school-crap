#JAVAPATH=/research/java/j2sdk1.4.2/bin/
#CLASSPATH=.:/research/java/j2sdk1.4.2/lib/classes.zip
CLASSPATH=.
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
TOPPACKAGE=PipeLayer
CLASSES:=$(patsubst %.java,%.class,$(wildcard $(TOPPACKAGE)/*.java))

TARGET=Game
ASSIGNMENT=CS330_Fall2008_Pipes


all: $(ASSIGNMENT).jar


%.class: %.java
	$(JAVAPATH)javac -nowarn $(JOPTIONS) $*.java

run: $(ASSIGNMENT).jar
	$(JAVAPATH)java  -cp $(ASSIGNMENT).jar $(TOPPACKAGE).$(TARGET)


runsample: 
	$(JAVAPATH)java -classpath '/home/cs330/Assignments/pipelayer/$(ASSIGNMENT).jar'  $(TOPPACKAGE).$(TARGET)

jar: $(ASSIGNMENT).jar


$(ASSIGNMENT).jar: $(CLASSES)
	find $(TOPPACKAGE) -name '*.class' | xargs jar cf temp.jar
#	mv temp.jar $(ASSIGNMENT).jar
	$(JAVAPATH)java -cp /home/zeil/src/retroguard-v2.2.0/retroguard.jar RetroGuard temp.jar $(ASSIGNMENT).jar retroguard.dat
	rm temp.jar 


index.html:
	mv pipes.html index.html

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
	-rm -f $(CLASSES) $(ZEILSOURCE)
	-rm -rf $(USER)/public_html/CS330_Fall2008_Pipes



