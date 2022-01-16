OC3_VEC 	EQU     $00D9	;pseudo-vector location for OC3 	
DATA    	EQU     $0	;Start of data segment
MAIN    	EQU     $100	;Start of code segment
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
ONE_CNT		EQU     16	;25 MS * 16 = .4 SEC
ZERO_CNT 	EQU     24      ;25 MS * 200 = .6 SEC
TIM_BASE 	EQU     50000	;0.5 US *20000 = 25 MS
OFFSET		EQU     10000	;0.5us *10000 = 5 ms

        ORG DATA	;initialize data
CNTR    RMB     1	;time base counter
STATE	RMB	1	;interrupt state variable
NUM	FCB	$00, $01, $02, $03, $04, $05, $06, $07, $08, $09, $0A, $0B, $0C, $0D, $0E, $0F
DAT	FCB 	$C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8; Hex value of displayed characters
COUNT   FCB	$00

	ORG MAIN
*DATA INITIALIZATION
	LDAA	#0	;CLEAR STATE AND COUNTER VALUES
	STAA	STATE
	STAA	CNTR
	STAA	COUNT	;count=0


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

INPUT	JSR	INCHAR			;GET CHARACTER FROM USER
	CMPA	#'Q'			;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP
	BRA	INPUT			;OTHERWISE GET INPUT AGAIN

ENDP	SWI

* OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 10 MS AND 
* DECREMENTS A COUNTER PROVIDES A TIME BASE EVENT EVERY 10 MS
*        
OC3_SVC LDAA    #OC3F         ;SET UP TO 
        STAA    TFLG1         ;CLEAR OC3F
        LDD     TOC3          ;SET UP FOR NEXT INTERRUPT
        ADDD   #TIM_BASE      ;ADD TIMEBASE (10 MS)?
        STD     TOC3          ;AND STORE 
*-----------------------------
	LDAA	STATE		;LOAD STATE
	CMPA	#1		
	BEQ	TWO		;STATE - 1 = 0? Y-> GO TO STATE TWO	
				;N-> GO TO STATE ONE


*       TWO STATE MACHINE       
ONE	LDAA 	CNTR		;IF CNTR NOT = 0
	BNE	OC3END		;EXIT INTERRUPT
	
        LDAA    #ONE_CNT       ;CNTR =  ONE_CNT 
        STAA    CNTR
	
	LDAA	#1		;SET NEXT STATE
	STAA	STATE
	
	LDX 	#NUM			; Points to the end of DAT
	LDY 	#DAT			; Points to the end of DAT2
	;BNE	RESET1
	;BRA	INPUT			;OTHERWISE GET INPUT AGAIN	
	
	LDAA	COUNT
COMP	LDAB	0, X
	CBA
	BEQ	DISPLAY
	INX
	INY
	BRA 	COMP

DISPLAY	LDAA	0, Y
	INC 	COUNT
	STAA	PORTB

	BRA 	OC3END


TWO     LDAA	CNTR
	BNE	OC3END	

        LDAA    #ZERO_CNT  	;CNTR = ZERO_CNT
        STAA    CNTR
	
	LDAA	#0		;SET NEXT STATE
	STAA	STATE

	LDAA	#$FF

        STAA	PORTB 		; Store A onto PortB  lo

OC3END	DEC     CNTR          ;DEC TIME BASE COUNTER
	RTI                   ;EXIT