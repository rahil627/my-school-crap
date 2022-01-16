OC3_VEC EQU     $00D9	;pseudo-vector location for OC3 	
DATA    EQU     $0	;start of data segment
MAIN    EQU     $100	;start of code segment
OUTA	EQU	$FFB8	;OUTA outputs accA ASCII character to terminal
INCHAR	EQU 	$FFCD

*6811 special register definitions
;PORTC   EQU     $1003
PORTB	EQU 	$1004	;counter/light (accB)
DDRC    EQU     $1007
TCNT    EQU     $100E	; Free running counter
TOC3    EQU     $101A	; Output compare register
TCTL1   EQU     $1020
TMSK1  	EQU     $1022
TFLG1   EQU     $1023

*masks for manipulating output compare interrupts
OC3F    EQU     %00100000
OC3I    EQU     %00100000

*some definitions used for time base and pulse width 
ONE_CNT		EQU     100        ;10 MS * 100 = 1 SEC
ZERO_CNT	EQU     200        ;10 MS * 200 = 2 SEC
TIM_BASE 	EQU     20000      ;0.5 US *20000 = 10 MS
OFFSET		EQU     10000      ;0.5us *10000 = 5 ms

        ORG DATA
CNTR    RMB     1	;time base counter
STATE	RMB	1	;interrupt state variable
HEX	FCB $C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8, $84, $89, $98; portB hex
        

	ORG MAIN
*DATA INITIALIZATION
	LDAA	#0	;clear state and counter variables
	STAA	STATE
	STAA	CNTR
	LDY 	#HEX;#$0002	; Points to the end of DAT2

*PORT C INITIALIZATION       
        ;LDAA    #%00000001	;SET UP PORTC_0 FOR 
        ;STAA    DDRC           ;OUTPUT
        ;CLRA                   ;AND 
        ;STAA    PORTC         	;OUTPUT A ZERO

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
	

INPUT	JSR	INCHAR			;GET CHARACTER FROM USER
	CMPA	#'Q'			;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP
	BRA	INPUT			;OTHERWISE GET INPUT AGAIN

ENDP	SWI

* OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 10 MS AND 
* DECREMENTS A COUNTER PROVIDES A TIME BASE EVENT EVERY 10 MS
*        
OC3_SVC LDAA    #OC3F         ;set up to
        STAA    TFLG1         ;clear OC3F

        LDD     TOC3          ;set up for next interrupt
        ADDD   #TIM_BASE      ;add timebase
        STD     TOC3          ;store
*-----------------------------
	LDAA	STATE		;load state
	CMPA	#1		;compare to 1
	BEQ	ONE		;STATE - 1 = 0? Y-> GO TO STATE TWO	
				;N-> GO TO STATE ONE

*       TWO STATE MACHINE       
ZERO	LDAA 	CNTR		;IF CNTR NOT = 0
	BNE	OC3END		;EXIT INTERRUPT
	
        LDAA    #ONE_CNT        ;CNTR =  ONE_CNT 
        STAA    CNTR
	
	LDAA	#1		;SET NEXT STATE
	STAA	STATE	
	
	;LDAA    #%00000001 	;SET UP FOR '1' OUT
        ;STAA    PORTC
	LDAA 0, Y	;load A with data where Y is pointing
	STAA PORTB	;store A onto PortB, B=A, light up what Y is pointing to
	INY

	BRA 	OC3END


ONE     LDAA	CNTR
	BNE	OC3END	

        LDAA    #ZERO_CNT  	;CNTR = ZERO_CNT
        STAA    CNTR
	
	LDAA	#0		;SET NEXT STATE
	STAA	STATE

	LDAA    #%00000000 	;SET UP FOR '0' OUT
        ;STAA    PORTC   
        STAA    PORTB   

OC3END	DEC     CNTR          ;DEC TIME BASE COUNTER
	RTI                   ;EXIT