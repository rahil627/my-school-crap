MAIN	EQU $0100
DATA	EQU $0000
INCHAR	EQU $FFCD
OUTA 	EQU $FFB8
DELIM	EQU ','
PORTB	EQU $1004
PORTE   EQU $100A

	ORG DATA
TERM	FCB $23
DAT     FCB $30, $31 ,$32 ,$33, $34, $35, $36, $37, $38 ,$39, $41


	ORG MAIN
	
	

INPUT	LDAA PORTE ;JSR INCHAR
	CMPA TERM
	BEQ EXIT

	LDX #$000B

COMP	LDAB 0, X
	CBA
	BEQ OUTB

	DEX
	BEQ INPUT
	BRA COMP
	
OUTB	STAA PORTB
	;LDAA DELIM
	;JSR OUTA
	BRA INPUT

EXIT	SWI