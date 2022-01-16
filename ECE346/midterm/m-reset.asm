OC3_VEC 	EQU     $00D9	;pseudo-vector location for OC3 	
DATA    	EQU     $0000	;Start of data segment
MAIN    	EQU     $0100	;Start of code segment
OUTA		EQU	$FFB8	;OUTA outputs accA ASCII character to terminal
INCHAR		EQU 	$FFCD	;INCHAR inputs ASCII character to accA and echo back, loops until it gets something

*6811 special register definitions
PORTB   	EQU     $1004	;7-segment display (accB)
;PORTC   	EQU     $1003
PORTE   	EQU 	$100A
DDRC    	EQU     $1007
TCNT    	EQU     $100E	;free running counter
TOC3    	EQU     $101A	;output compare register
TCTL1   	EQU     $1020
TMSK1  		EQU     $1022
TFLG1   	EQU     $1023

*Masks for manipulating output compare interrupts
OC3F    	EQU     %00100000
OC3I    	EQU     %00100000

*Some definitions used for time base and pulse width 
ONE_CNT		EQU     16	;25ms  *  16  = .4s
ZERO_CNT 	EQU     24      ;25ms  *  200 = .6s
TIM_BASE 	EQU     50000	;0.5us *20000 = 25ms
OFFSET		EQU     10000	;0.5us *10000 =  5ms

        ORG DATA	;initialize data
CNTR    RMB     1	;time base counter
STATE	RMB	1	;interrupt state variable
NUM	FCB	$00, $01, $02, $03, $04, $05, $06, $07, $08, $09, $0A, $0B, $0C, $0D, $0E, $0F ;used to compare?
DAT	FCB 	$C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8 ;hex value of displayed characters
COUNT   FCB	$00
;15INHEX	FCB	$0F

	ORG MAIN
*DATA INITIALIZATION (clear state and counter values)
	LDAA	#0	;load 0 into accA
	STAA	STATE	;state=0
	STAA	CNTR	;cntr=0
	STAA	COUNT	;count=0

	LDX 	#NUM		;points to the beginning of NUM
	LDY 	#DAT		;points to the beginning of DAT2


*PORT C INITIALIZATION       
        ;LDAA    #%00000001      	;SET UP PORTC_0 FOR 
        ;STAA    DDRC            	;OUTPUT
        ;CLRA                    	;AND 
        ;STAA    PORTC         	 	;OUTPUT A ZERO

*---------------- Interrupt initialization ----------------------------

*JUMP TABLE INITIALIZATION
        LDAA    #$7E            	;SET UP OC3 JUMP TABLE    
        STAA    OC3_VEC         	;FOR JMP
        LDD     #OC3_SVC        	; TO
        STD      OC3_VEC+1       	;OC3 INTERRUPT SERVICE

*OC3 INTERRUPT INITIALIZATION        
        LDAA    #OC3I           	;SET UP OC3 INTERRUPT
        STAA    TMSK1           	;ENABLE OC3 IN MASK REGISTER
        LDAA    #OC3F           	;SET UP TO CLEAR
        STAA    TFLG1           	;OC3  INTERRUPT FLAG

*TOC3 INITIALIZATION        
        LDD     TCNT            	;GET CURRNT VALUE OF TCNT
        ADDD   #OFFSET         		;ADD 5 MS OFFSET
        STD     TOC3            	;SET UP  TOC3

        CLI                     	;ENABLE SYSTEM INTERRUPT
*--------------- End Interrupt initialization ------------------------
	;LDX	#NUM
	;LDY	#DAT	

INPUT	JSR	INCHAR	;GET CHARACTER FROM USER

	CMPA	#'+'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'-'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'D'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'H'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'R'	;IF = Q THEN EXIT PROGRAM
	BEQ	RESET

	CMPA	#'S'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'E'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'P'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'N'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'F'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'Q'	;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	BRA	INPUT	;OTHERWISE GET INPUT AGAIN

ENDP	SWI

* OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 10 MS AND 
* DECREMENTS A COUNTER PROVIDES A TIME BASE EVENT EVERY 10 MS
*        
OC3_SVC LDAA    #OC3F		;SET UP TO 
        STAA    TFLG1		;CLEAR OC3F
        LDD     TOC3		;SET UP FOR NEXT INTERRUPT
        ADDD   #TIM_BASE	;ADD TIMEBASE (10 MS)?
        STD     TOC3		;AND STORE 
*-----------------------------
	LDAA	STATE		;LOAD STATE
	CMPA	#1		
	BEQ	TWO		;STATE - 1 = 0? Y-> GO TO STATE TWO	
				;N-> GO TO STATE ONE


;INPUT	JSR INCHAR ; LDAA PORTE ;Read input from bufflo
;	CMPA TERM  ; Compare it to the termination symbol  (A-$23)
;	BEQ EXIT   ; If it is the same then exit (=0 ?) check Z CCR bit	

;	LDX #$0014 ; Point X to the end of my data	

;COMP	LDAB 0, X  ; Load B with data where X is pointing 
	;CBA	   ; Compare B to A
	;BEQ OUTB   ; If they are the same, then print it out on PortB
 
	;DEX	   ; X = X -1 (Point X to the next value of my data)
	;BEQ ERROR  ; If X = 0 then no match was found, go to error display
	;BRA COMP   ; otherwise loop


*       TWO STATE MACHINE       
ONE	LDAA 	CNTR		;if (CNTR != 0)
	BNE	OC3END		;exit interrupt
	
        LDAA    #ONE_CNT	;CNTR = ONE_CNT 
        STAA    CNTR
	
	LDAA	#1		;SET NEXT STATE (STATE=1)
	STAA	STATE
	
	;why is this here?
	;LDX 	#NUM		;points to the beginning of NUM
	;LDY 	#DAT		;points to the beginning of DAT2
	
	LDAA	COUNT		;a=count
COMP	LDAB	0, X		;b=x
	CBA			;count==x? [compare b to a]
	BEQ	DISPLAY		;branch if they do not equal each other [branch if equal to zero]
	INX			;x++
	INY			;y++
	BRA 	COMP		;branch to comp [branch always]

DISPLAY	LDAA	0, Y		;a=y
	INC 	COUNT		;count++
	STAA	PORTB		;portb=a
	
	;if(count==15) count=0
	LDAB	#16 		;hmmm why 16?
	CMPB	COUNT		;if(count==16) reset
	BEQ	RESET

	BRA 	OC3END		;branch to oc3end (exit interrupt)

RESET	LDAB	#0		;count=0
	STAB	COUNT
	;LDX	#NUM		;why don't i need to do this?
	;LDY	#DAT
	BRA	INPUT		;go back to input loop

TWO     LDAA	CNTR
	BNE	OC3END	

        LDAA    #ZERO_CNT  	;cntr=#zero_cnt
        STAA    CNTR
	
	LDAA	#0		;set next state (state=0)
	STAA	STATE

	LDAA	#$FF
        STAA	PORTB		;store (read: put) A onto portb
	;JSR	OUTA		;display A in console

OC3END	DEC     CNTR		;cntr--
	RTI			;

*notes
*BRA vs JSR? BRA is used for closer/relative addresses
*EQU vs FCB
*loop from example one vs our loop