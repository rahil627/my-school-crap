
In this assignment you will implement part a system for drawing pictures composed of a collection of basic shapes.
You will be looking at the process of working with inheritance and dynamic binding from both the point of view of the application writer and the class developer.


The application consists of an inheritance hierarchy with base class Shape, a handful of subclasses of Shape, a Picture class that serves as a container of Shapes, and a main program test driver to exercise the Picture class.


In most figure drawing programs, it is possible to form a new shape object by selecting a group of existing shapes and "grouping" them to form a single Composite shape.
That composite object can then be moved, scaled, reflected, etc., as if it were a single entity.

Your task is to complete the implementation of a Composite class in this Shape hierarchy.
