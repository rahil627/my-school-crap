MAIN	EQU $0100	;executable program begins at (hex)110
DATA	EQU $0000	;data segment begins at (hex)000
INCHAR	EQU $FFCD	;utility subroutine that waits for input
OUTA 	EQU $FFB8	;utility program for outputting ASCII (terminal/display)
DELIM	EQU ','		;delimiter symbol
PORTB	EQU $1004	;counter/light (accB)
PORTE   EQU $100A	;input port (accA)

	ORG DATA ;initialize data
PROMPT	FCC 'hexadecimal:_~' ;'please enter a hexidecimal: '
;ERROR	FCC '_cannot display that character'
TERM	FCB $23	;create ASCII code for "#", termination symbol
TERM2	FCB '~' ;used in outstr subroutine
ASCII   FCB $30, $31 ,$32 ,$33, $34, $35, $36, $37, $38, $39, $41, $62, $43, $64, $45, $46, $67, $48, $50 ;Data starting from $02 until $0B
HEX	FCB $C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8, $84, $89, $98 ;port B hex

	;ORG PORTB
	;FCB $FF    	;put nothing in LED before program execution

	ORG MAIN	;main()
INPUT	LDAA #$000A  	;load new line
	JSR OUTA	;jump to sub routine OUTA - display the new line
	LDX #PROMPT  	;load x with prompt
	JSR OUTSTR

	JSR INCHAR	;wait for keyboard input to ACCA
	;LDAA PORTE 	;read PortE (input port)
	CMPA TERM  	;if(termination symbol [A-$23])
	BEQ EXIT   	;then exit
	LDX #$001F	;point x to the end of ASCII data [31] 12+31 why does it start at 12?
	LDY #$0032	;point y to the end of HEX data [50] 31+19 chars

COMPARE	LDAB 0, X  	;load B with data where X is pointing
	CBA	   	;compare B to A
	BEQ OUTB   	;if they are the same, then print it out on PortB
 	DEX	   	;x-- (point x to the next value of my data)
 	DEY	   	;y-- (point y to the next value of my data)
	BEQ DP  	;if X = 0 then no match was found, display a decimal point and go to the beginning*
	BRA COMPARE   	;else(loop)
	
OUTB	LDAA 0, Y	;load A with data where Y is pointing
	STAA PORTB 	;store A onto PortB, B=A, light up what Y is pointing to
	LDAA DELIM	;load delimiter
	JSR OUTA	;display delimiter
	BRA INPUT  	;branch back to INPUT (loop)

DP	LDAA #$007F	;load DP
	STAA PORTB 	;light DP
	;LDAA #ERROR	;load error message
	;JSR OUTA   	;display error message
	LDAA #DELIM	;load delimiter
	JSR OUTA   	;display delimiter
	BRA INPUT  	;send back to beginning

OUTSTR	LDAA 0,X	;expecting that X points to the start of the data (prompt)
	CMPA TERM2	;compare character in prompt to termination character '~'
	BEQ ENDSR	;if true, end OUTSTR
	JSR OUTA	;jump to sub routine OUTA
	INX		;increment X
	BRA OUTSTR	;start OUTSTR loop over again until all characters are displayed to terminal

ENDSR	RTS

EXIT	SWI	   	;end