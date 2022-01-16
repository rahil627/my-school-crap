MAIN	EQU $0100	;executable program begins at (hex)110
DATA	EQU $0000	;data segment begins at (hex)000
INCHAR	EQU $FFCD	;utility subroutine that waits for input
OUTA 	EQU $FFB8	;utility program for outputting ASCII
DELIM	EQU ','		;delimiter symbol
PORTB	EQU $1004	;display (accB) counter
PORTE   EQU $100A	;input port (accA) terminal

	ORG DATA ;initialize data
PROMPT	FCC 'please enter a hexidecimal: '
TERM	FCB $23	;Create ASCII code for "#", termination symbol
TERM1	FCB '>'
ASCII   FCB $30, $31 ,$32 ,$33, $34, $35, $36, $37, $38, $39, $41, $62, $43, $64, $45, $46, $67, $48, $50 ;Data starting from $02 until $0B
HEX	FCB $C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8, $84, $89, $98 ;port B hex

	ORG MAIN	;main()
INPUT	LDAA #$000A  	;load new line
	JSR OUTA	;jump to sub routine OUTA - display the new line
	LDX #PROMPT  	;load x with prompt 'please enter a hexidecimal:'
	JSR OUTSTR
	;LDAA 0,X	;expecting that x points to the start of the data (prompt)

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
	BEQ DECM  	;if X = 0 then no match was found, display a decimal point and go to the beginning*
	BRA COMPARE   	;else(loop)
	;use switch case with ASCII
	
OUTB	LDAA 0, Y	;load A with data where Y is pointing
	STAA PORTB 	;store A onto PortB, B=A
	LDAA DELIM	;load ASCII character for ","
	JSR OUTA	;output delimiter to screen
	BRA INPUT  	;branch back to INPUT (loop)

DECM	 
	;NEGB		; Complement B
	;ORAB #%1000000 ; 'OR' 1000000 onto B
	;NEGB		; Complement B again to have last used Hex character on display with DP lit
	LDAA #$007F; Load Hex in A for Decimal light on 7-segment display (Decimal point means error message on 7-segment display)
	STAA PORTB ; Put Decimal lightup onto PortB
	LDAA #$003F; Load Hex for "?" into A to display as error message on screen
	JSR OUTA   ; Jump to sub routine OUTA
	LDAA #DELIM; Load Accumlator A with #DELIM
	JSR OUTA   ; Jump to sub routine OUTA
	BRA INPUT  ; Send back to beginning

OUTSTR	LDAA 0,X	; Expecting that X points to the start of the data (prompt)
	CMPA TERM1	; Compare character in prompt to termination character '>'
	BEQ ENDSR	; If true, end OUTSTR
	
	JSR OUTA	; Jump to sub routine OUTA
	INX		; Increment X
	BRA OUTSTR	; Start OUTSTR loop over again until all characters are displayed to terminal

ENDSR	RTS

EXIT	SWI	   	;end