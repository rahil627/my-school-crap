Welcome to MGTEK’s MiniIDE!

Congratulations on installing MiniIDE. We hope that the installation was 
easy and that you will enjoy the software.

Motorola is a registered trademark of Motorola Inc.
Windows, Windows 95/98/Me/NT/2000/XP are registered trademarks of Microsoft 
Inc.
Other brand and product names are either trademarks or registered 
trademarks of their respective holders.

Contents
--------
1. Introduction
2. System Requirements
3. Files installed by setup
4. Tips, Hints and Known Issues
5. What’s new?


1. Introduction
---------------
MiniIDE is a Mini Integrated Development Environment, designed especially 
for Motorola's HC12 EVB M68EVB912B32. It incorporates an editor, terminal 
window and an integrated cross assembler. Source files can easily edited, 
assembled and downloaded into the HC12. This makes it ideal for small 
projects, which run directly on the EVB. 


2. System Requirements
----------------------
MiniIDE is designed to run under Windows 98/Me or Windows NT 4.0/2000/XP 
(it won't run under Windows 3.x/Windows 95). There are no special 
requirements. You need a free communication port if you want to download 
software into the EVB.


3. Files installed by setup
---------------------------
miniide.exe	main program
asm11.exe	command line assembler HC11
asm12.exe	command line assembler HC12
mgspawn.exe	spawn utility (used by miniide.exe)
miniide.chm	help file
readme.txt	this file
example.asm	a example 68HC12 assembler file
hc12.inc	a include files which contains common 68HC12 definitions 


4. Tips, Hints and Known Issues
-------------------------------
The terminal loses characters during upload and extensive (hardware) screen 
scrolling. This problem occurs only in Windows 95/98 and certain hardware 
configurations.

For other information, check the FAQ at the mgtek.com web-site.

5. What’s new?
-------------
V1.01, January 1998 first alpha release
V1.03, March 1998 initial beta release
V1.05, August 1998 second beta release
-	Re-implemented using MFC 4.2
-	Multiple document interface
-	Output window with ’go to error line’ function
-	some cosmetics: office 97 style menus, toolbars, docking windows
-	several bug fixes and improvements
V1.07, December 1998 third beta release
-	Several improvements of the assembler (multi-pass, conditional 
assembly, macro functionality)
-	Syntax highlighting editor (slow, not recommended for large files)
V1.08, January 2000
-	Yearly update
-	Terminal window does not display NUL characters ^@
V1.09, June 2000
-	Assembler listing file output with Windows text line endings
-	Added CR/LF line endings option in terminal window
-	Added character and line delays to terminal window (experimental)
V1.10, January 2002
-	Yearly update
-	Updated to assembler ASM V1.14
-	Added support for themes in Windows XP
V1.11, February 2002
-	Added support for new dialogs
-	Added undo/redo capabilities to the editor
-	Improved syntax color highlighting
V1.14, December 2002
-	Yearly update
-	Miscellaneous minor bug fixes
V1.16, September 2003
-	Yearly update
-	Fixed support for USB serial COM adapters
V1.17, September 2004
-	Yearly update

