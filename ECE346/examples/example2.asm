*  EQUATES
DATA  EQU  $0100
MAIN  EQU  $0120

	ORG DATA

A1	FCB   $3A, $10, $30
A2    	FCB   $02, $46, $21
SUM   	RMB   3
CNTR  	RMB   1

	ORG MAIN

Init	LDX #A1
	LDY #A2
	LDAA #3
	STAA CNTR
	CLC

Loop	LDAA 2,X
	ADCA 2,Y
	STAA 8,X

	DEX
	DEY
	DEC CNTR
	BNE Loop

Exit	SWI