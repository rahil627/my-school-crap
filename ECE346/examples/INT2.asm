OC3_VEC 	EQU     $00D9	; pseudo-vector location for OC3 	
DATA    	EQU     $0	; Start of data segment
MAIN    	EQU     $100	; Start of code segment

OUTA		EQU	$FFB8
INCHAR		EQU 	$FFCD

* 6811 special register definitions

PORTC   	EQU     $1003
DDRC    	EQU     $1007

TCNT    	EQU     $100E	; Free running counter

TOC3    	EQU     $101A	; Output compare register

TCTL1   	EQU     $1020
TMSK1  		EQU     $1022
TFLG1   	EQU     $1023

* Masks for manipulating output compare interrupts
OC3F    	EQU     %00100000
OC3I    	EQU     %00100000
* Some definitions used for time base and pulse width 

ONE_CNT		EQU     100       	   ;10 MS * 100 = 1 SEC
ZERO_CNT 	EQU     200        ;10 MS * 200 = 2 SEC
TIM_BASE 	EQU     20000      ;0.5 US *20000 = 10 MS
OFFSET		EQU     10000      ;0.5us *10000 = 5 ms

        ORG DATA
CNTR    RMB     1               	;TIME BASE COUNTER (MESSAGE)?
        

	ORG MAIN

*PORT C INITIALIZATION       
        LDAA    #%00000001      	; SET UP PORTC_0 FOR 
        STAA    DDRC            	;OUTPUT
        CLRA                    	;AND 
        STAA    PORTC         	 	;OUTPUT A ZERO

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


*       TWO STATE MACHINE       
REENTR  LDAA    #%00000001 ;SET UP FOR '1' OUT
        STAA    PORTC
        LDAA    #ONE_CNT   ;SET UP 1 SECOND DELAY 
        STAA    CNTR

LOOP1   LDAA    CNTR       ;CHECK COUNTER  
        BNE     LOOP1      ;WAIT ON ZERO

        LDAA    #%00000000 ;SET UP FOR '0' OUT
        STAA    PORTC

        LDAA    #ZERO_CNT  ;SET UP 2 SEC DELAY
        STAA    CNTR

LOOP0   LDAA    CNTR       ;CHECK COUNTER 
        BNE     LOOP0      ;WAIT IF NOT ZERO
        BRA     REENTR     ;ELSE START OVER-ENDLESS LOOP




* OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 10 MS AND 
* DECREMENTS A COUNTER PROVIDES A TIME BASE EVENT EVERY 10 MS
*        
OC3_SVC LDAA    #OC3F         ;SET UP TO 
        STAA    TFLG1         ;CLEAR OC3F

        LDD     TOC3          ;SET UP FOR NEXT INTERRUPT
        ADDD   #TIM_BASE      ;ADD TIMEBASE (10 MS)?
        STD     TOC3          ;AND STORE 

	LDAA 	#'A'
	JSR     OUTA
	LDAA 	#$0D
	JSR	OUTA 

        DEC     CNTR          ;DEC TIME BASE COUNTER

        RTI                   ;EXIT