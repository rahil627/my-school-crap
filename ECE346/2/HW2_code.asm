MAIN	EQU $0100
DATA	EQU $0000
INCHAR	EQU $FFCD ;INCHAR inputs ASCII character to AccA and echo back, loops until it gets something
OUTA 	EQU $FFB8 ;OUTA outputs AccA ASCII character to terminal
DELIM	EQU ','
PORTB	EQU $1004
PORTE   EQU $100A

	ORG DATA
PROMPT	FCC 'Character=>'
TERM1	FCB '>'
TERM2	FCB $23 ; HEX(23) = ASCII(#)  could have done FCB '#'
DAT     FCB $30, $31 ,$32 ,$33, $34, $35, $36, $37, $38 ,$39, $41, $62, $43, $64, $45, $46, $67, $48, $50 ; Data starting from $02 until $13
DAT2	FCB $C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8, $84, $89, $98 ; Hex value of displayed characters


	ORG PORTB
	FCB $FF    ; Put nothing in LED before program execution

	ORG MAIN
	     	

INPUT	LDAA #$000A  ; Load new line command
	JSR OUTA     ; Jump to sub routine OUTA
	LDX #PROMPT  ; Load X with prompt text
	JSR OUTSTR   ; Jump to OUTSTR function to output prompt text character by character
	;LDAA #$000A
	;JSR OUTA

	JSR INCHAR ; Jump to sub routine INCHAR
	CMPA TERM2  ; Compare it to the termination symbol  (A-$23)
	BEQ EXIT   ; If it is the same then exit (=0 ?) check Z CCR bit	

	LDX #$001F   ; Point X to the end of my data
	LDY #$0032   ; Point Y to the end of Hex data

COMP	LDAB 0, X  ; Load B with data where X is pointing
	CBA        ; Compare B to A
	BEQ OUTB   ; If they are the same, then print it out on PortB
	
	DEX	   ; X = X -1 (Point X to the next value of my data)
	DEY        ; Y = Y -1 (Point X to the next value of my data)
	BEQ DECM   ; If X = 0 then no match was found, Go to DECM function to display DP
	BRA COMP   ; otherwise loop

OUTB	LDAA 0, Y
	;LDAB 0, Y  ;Try to Load the correct Hex display Character into B to be used in the DECM function
	STAA PORTB ;Store A onto PortB	
	LDAA #DELIM;Load Accumlator A with #DELIM
	JSR OUTA   ;Jump to sub routine OUTA
	BRA  INPUT  ;Go to the beginning
	

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

EXIT	SWI        ;End 

