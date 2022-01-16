MAIN	EQU $0100	;executable program begins at (hex)110
DATA	EQU $0000	;data segment begins at (hex)000
INCHAR	EQU $FFCD	;INCHAR inputs ASCII character to accA and echo back, loops until it gets something
OUTA 	EQU $FFB8	;OUTA outputs accA ASCII character to terminal
DELIM	EQU ','		;delimiter symbol
PORTB	EQU $1004	;counter/light (accB)
PORTE   EQU $100A	;input port (accA)

	ORG DATA	;initialize data
PROMPT	FCC 'Character=>'	;'please enter a hexidecimal: '
TERM1	FCB '>'		;create ASCII code for "#", termination symbol
TERM2	FCB $23 ; HEX(23) = ASCII(#)  could have done FCB '#'
DAT     FCB $30, $31 ,$32 ,$33, $34, $35, $36, $37, $38 ,$39, $41, $62, $43, $64, $45, $46, $67, $48, $50 ; Data starting from $02 until $13
DAT2	FCB $C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8, $84, $89, $98 ; portB hex


	ORG PORTB
	FCB $FF    ; Put nothing in LED before program execution

	ORG MAIN	;main()     	
INPUT	LDAA #$000A	;load new line
	JSR OUTA     	;jump to sub routine OUTA - display the new line
	LDX #PROMPT  	;load x with prompt
	JSR OUTSTR   	;jump to OUTSTR function to output prompt text character by character

	JSR INCHAR 	;jump to sub routine INCHAR - wait for keyboard input to ACCA
	;LDAA PORTE 	;read PortE (input port)
	CMPA TERM2  	;if(termination symbol [A-$23])
	BEQ EXIT   	;then exit (=0 ?) check Z CCR bit	

	LDX #$001F   	;point x to the end of ASCII data [31] 12+31 why does it start at 12?
	LDY #$0032   	;point y to the end of HEX data [50] 31+19 chars

COMP	LDAB 0, X	;load B with data where X is pointing
	CBA		;Compare B to A
	BEQ OUTB	;If they are the same, then print it out on PortB
	
	DEX		;x-- (point x to the next value of my data)
	DEY		;y-- (point y to the next value of my data)
	BEQ DECM	;if X = 0 then no match was found, display a decimal point and go to the beginning
	BRA COMP	;else(loop)

OUTB	LDAA 0, Y	;load A with data where Y is pointing
	STAA PORTB	;store A onto PortB, B=A, light up what Y is pointing to
	LDAA #DELIM	;load delimiter
	JSR OUTA	;display delimiter
	BRA  INPUT	;branch back to INPUT (loop)

DECM	LDAA #$007F	;load DP
	STAA PORTB	;light DP
	LDAA #$003F	;load hex for "?" into A to display as error message on screen
	JSR OUTA	;display error message
	LDAA #DELIM	;load delimiter
	JSR OUTA   	;display delimiter
	BRA INPUT  	;send back to beginning 


OUTSTR	LDAA 0,X	;Eexpecting that X points to the start of the data (prompt)
	CMPA TERM1	;compare character in prompt to termination character '>'
	BEQ ENDSR	;if true, end OUTSTR
	
	JSR OUTA	;jump to sub routine OUTA
	INX		;increment X
	BRA OUTSTR	;start OUTSTR loop over again until all characters are displayed to terminal

ENDSR	RTS

EXIT	SWI        	;end 
