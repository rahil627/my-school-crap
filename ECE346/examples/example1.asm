MAIN  EQU  $0100
A1    EQU  $0150
A2    EQU  $0170
CNTR  EQU  $0140
	
	
	ORG MAIN

	LDX  #A1   ;X=$0150
	LDY  #A2   ;Y=$0170
	LDAA #10
	STAA CNTR  ;CNTR= 10   STAA $1040
	
Loop	LDAA 0,X	
      	STAA 0,Y
      	INX
      	INY
      	DEC CNTR	; DEC $1040
      	BNE Loop

      	SWI