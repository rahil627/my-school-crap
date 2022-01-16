MAIN	EQU $0100
DATA	EQU $0000
INCHAR	EQU $FFCD
OUTA 	EQU $FFB8
DELIM	EQU ','
PORTB	EQU $1004
PORTE   EQU $100A

	ORG DATA
TERM	FCB $23	; HEX(23) = ASCII(#)  could have done FCB '#'
DAT     FCB $30, $31 ,$32 ,$33, $34, $35, $36, $37, $38 ,$39, $41 ; Data starting from $02 until $0B


	ORG MAIN
	
	

INPUT	LDAA PORTE ; Read PortE
	CMPA TERM  ; Compare it to the termination symbol  (A-$23)
	BEQ EXIT   ; If it is the same then exit (=0 ?) check Z CCR bit	

	LDX #$000B ; Point X to the end of my data	

COMP	LDAB 0, X  ; Load B with data where X is pointing 
	CBA	   ; Compare B to A
	BEQ OUTB   ; If they are the same, then print it out on PortB
 
	DEX	   ; X = X -1 (Point X to the next value of my data)
	BEQ INPUT  ; If X = 0 then no match was found, go back to the beginning 
	BRA COMP   ; otherwise loop
	
OUTB	STAA PORTB ;Store A onto PortB	
	BRA INPUT  ;Go to the beginning

EXIT	SWI	   ;End 	