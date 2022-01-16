DATA	EQU $0000
MAIN	EQU $0100
INCHAR	EQU $FFCD
OUTA 	EQU $FFB8
PORTB	EQU $1004

	ORG DATA

D1	FCC 'Prompt Example yabadabadoo>'
TERM	FCB '~'
D2	FCC 'Yet Another Prompt>'
TERM2	FCB '~'

	ORG MAIN


	LDX #D1
	JSR OUTSTR

	LDX #D2
	JSR OUTSTR


ENDPROG	SWI




OUTSTR	LDAA 0,X	; Expecting that X points to the start of the data (prompt)
	CMPA TERM
	BEQ ENDSR
	
	STAA PORTB ;JSR OUTA
	INX
	BRA OUTSTR

ENDSR	RTS