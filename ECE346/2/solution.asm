*****
* ECE 446/546  Spring 2008
* Solution to Homework 2
* Created by Dr. Belfore 2/7/08
*****EQUATES***********
DATA     EQU  $100      ;Data segment begins at (hex)100
MAIN     EQU  $140      ;Executable program begins at (hex)150
INCHAR   EQU  $FFCD     ;Utility Subroutine WAITS FOR INPUT
OUTA     EQU  $FFB8     ;Utility program for outputting ASCII
OUTCRLF  EQU  $FFC4     ;Utility program for outputting CRLF
OUTSTRG  EQU  $FFC7     ;Utility program for outputting CRLF
OUTSTRGO EQU  $FFCA     ;Utility program for outputting CRLF
DELIM    EQU  ','       ;Delimiter ASCII symbol is ","
PORTB    EQU  $1004
*
         ORG     DATA
*
*table including acceptable characters that are not digits
PROMPT   FCC   'Input Character Please > '
         FCB   $04
CHARTAB  FCC   'AbCdEFSgHP'

* pins on man74:
*  a 14
*  b 13
*  c 8
*  d 7
*  e 6
*  f 1
*  g 2
*  . 9

* table holding 7 segment display patterns
* show which segments are to be on.  Complemented
* in code below
*            .gfedcba
DISPTAB FCB %00111111 	; 0
        FCB %00000110 	; 1
        FCB %01011011 	; 2
        FCB %01001111 	; 3
        FCB %01100110 	; 4
        FCB %01101101 	; 5
        FCB %01111101 	; 6
        FCB %00000111 	; 7
        FCB %01111111 	; 8
        FCB %01101111 	; 9
        FCB %01110111 	; A
        FCB %01111100 	; b
        FCB %00111001 	; C
        FCB %01011110 	; d
        FCB %01111001 	; E
        FCB %01110001 	; F
        FCB %01101101 	; S
        FCB %01101111 	; g
        FCB %01110110 	; H
        FCB %01110011 	; P

	
TERM    FCB   '#'		;Create ASCII code for "#"
*
*
        ORG   MAIN
LOOP    LDX   #PROMPT
	JSR   OUTSTRGO
	jsr   INCHAR		; Wait for keyboard input  to ACCA
        cmpa  TERM		; Is it the termination character ?
        beq   EXIT		; If it is, exit, else
	psha
	JSR   OUTCRLF
	pula
        jsr   getIndex		; give index<0 is cannot display
	blt   WRONG
	ldx   #DISPTAB		; get beginning of display table
	abx			; calculate address of the table entry we want
	ldaa  0,X		; get the pattern from the table
        coma                  	; needs to be complemented
	staa  PORTB		; output it to the output port
        bra   LOOP		; Branch back to repeat
WRONG   ldaa  PORTB
	bpl   LOOP
	suba  #$80
        staa  PORTB
        bra   LOOP
EXIT    swi			; Exit  Program

*  Get index is a subroutine that determines the index into the display table
* for legal inputs.  The input character is must be in accumulator A and the index into
* the table is in accumulator B.  If the character is not legal, -1 is returned in B

* first check to see if the input character is a digit
getIndex
	cmpa #'0'		; check smallest digit
	blt RET1		; if too small, not anything we can display
	cmpa #'9'		; check largest digit
	bgt Search		; not a digit, search the list
	suba #'0'		; calculate the entry in the table
	tab			; note that we need make sure the order of the table is consistent.
	bra  RET
Search	
	ldab #10		; table is 10 characters long
	ldx  #CHARTAB		; beginning of table
SCANLOOP 
	cmpa 0,x		; compare input character with character in table
	beq  Match		; check if equal--if so, we have found it.
	inx			; otherwise, advance to next table entry
	decb			; loop maintenence
	bge SCANLOOP		; have we checked all entries in the table?
RET1    ldab #-1		; if we have, input character was not in the table
	bra RET
Match   negb
	addb #20		; calculate correct index into the table
RET	rts