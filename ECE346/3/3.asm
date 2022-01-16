*note: program is incomplete

*Program generates PWM controlled sequence of 1 sec on ("bright") and 2 sec off ("dim").
*The  LED light is "bright" with a 90%
*Duty cycle and "dim" with a 30% duty cycle.
*Uses OC3 (PA_5)for output.

*EQUATES:
DATA    EQU     $00
MAIN    EQU     $100
OC1_VEC EQU     $DF
TCNT    EQU     $100E
TOC1    EQU     $1016
TOC3    EQU     $101A
OC1M    EQU     $100C
OC1D    EQU     $100D
TCTL1   EQU     $1020
TMSK1   EQU     $1022
TFLG1   EQU     $1023
OC1F    EQU     %10000000
OC1I    EQU     %10000000

OC3I    EQU     %00100000
OC3F    EQU     %00100000

ONE_CNT  EQU      100   ;10 MS * 100 = 1 SEC
ZERO_CNT EQU      200   ;10 MS * 200 = 2 SEC
TIM_BASE EQU    20000   ;0.5 US *20000 = 10 MS
T_90     EQU    18000   ;90% duty cycle @(T=10MS) = 9 ms = 18000
T_40     EQU     8000
OFFSET   EQU     1000   ;.5 ms offset


	ORG DATA
CNTR     	RMB     1   ;TIME BASE COUNTER (MESSAGE)
PULS_WIDTH 	RMB  	2   ;PULSEWIDTH/TIMEBASE = DUTYCYCLE(MESSAGE)

CNTR2		RMB	1
DATA2		RMB	1


	ORG MAIN
;initializations
*JUMP TABLE INITIALIZAITON
        LDAA    #$7E            ;SET UP OC1 JUMP TABLE    
        STAA    OC1_VEC         ;FOR JMP
        LDD     #OC1_SVC        ;TO
        STD     OC1_VEC+1       ;OC1 INTERRUPT SERVICE
*PWM INITIALIZATION   
        LDAA    #%00100000      ;SET UP OC1M 
        STAA    OC1M            ;AND OC1D TO PRODUCE  A 1 ON PA_5 (OC3)
        STAA    OC1D            ;AND 
        LDAA    #%00100000      ;SET UP OC3 TO CLEAR (PA_5)
        STAA    TCTL1           ;ON TOC3 COMPARE
*TOC1 INITIALIZATION        
        LDD     TCNT            ;GET CURRENT VALUE OF TCNT
        ADDD     #OFFSET        ;ADD .5 MS OFFSET TO
        STD     TOC1            ;SET UP  TOC1 FOR FIRST INTERRUPT
*OC1 INTERRUPT INITIALIZATION        
        LDAA    #OC1I           ;SET UP OC1 INTERRUPT
        STAA    TMSK1           ;ENABLE OC1 IN MASK REGISTER
        LDAA    #OC1F           ;SET UP TO CLEAR
        STAA    TFLG1           ;OC1  INTERRUPT FLAG
*OC3 INTERRUPT INITIALIZATION        
        LDAA    #OC3I           ;SET UP OC3 INTERRUPT
        STAA    TMSK1           ;ENABLE OC3 IN MASK REGISTER
        LDAA    #OC3F           ;SET UP TO CLEAR
        STAA    TFLG1           ;OC3  INTERRUPT FLAG

        CLI                     ;ENABLE SYSTEM INTERRUPT

;main
CLCNTR2	CLR	CNTR2		;CLEAR COUNTER
LOOP	INC	CNTR2		;if(cntr<50){cntr++} else {cntr=0}
	LDAA	CNTR2
	CMPA	#50
	BEQ	CLCNTR2
	BNE	LOOP
	;count up/down

;interrupts
*
*       TWO STATE MACHINE       
*
STATE1  LDD     #T_90           ;SET UP FOR 90% DC
        STD     PULS_WIDTH      ; 
        LDAA    #ONE_CNT        ;SET UP 1 SECOND DELAY 
        STAA    CNTR
LOOP1   TST     CNTR            ;CHECK COUNTER  
        BNE     LOOP1           ;WAIT FOR ZERO COUNT
        
STATE0  LDD     #T_30           ;SET UP FOR 30%VDC
        STD     PULS_WIDTH
        LDAA    #ONE_CNT       ;SET UP 2 SEC DELAY
        STAA    CNTR
LOOP0   TST     CNTR            ;CHECK COUNTER 
        BNE     LOOP0           ;WAIT FOR ZERO COUNT
        BRA     STATE1          ;ELSE START OVER -ENDLESS LOOP
    
*       OC1 INTERUPPT SERVICE ROUTINE
*       INTERRUPTS EVERY 10 MS AND DECREMENTS A COUNTER
*       PROVIDES A TIME BASE EVENT EVERY 10 MS
*       SETUPS TOC3 FOR COMPARE AFTER (PUL_WIDTH) TIME UNITS 
    
OC1_SVC LDAA    #OC1F           ;SET UP TO 
        STAA    TFLG1           ;CLEAR OC1F

        LDD     PULS_WIDTH      ;SET UP PULSE WIDTH
        ADDD    TOC1            ;ADD CURRENT LOCATION
        STD     TOC3            ;AND STORE IN TOC3

        LDD     TOC1            ;SET UP FOR NEXT INTERRUPT
        ADDD    #TIM_BASE       ;ADD TIMEBASE (10 MS)
        STD     TOC1            ;AND STORE

        DEC     CNTR            ;DEC TIME BASE COUNTER
        RTI                     ;EXIT

OC3_SVC LDAA    #OC3F         	;SET UP TO 
        STAA    TFLG1         	;CLEAR OC3F

        LDD     TOC3          	;SET UP FOR NEXT INTERRUPT
        ADDD    #TIM_BASE      	;ADD TIMEBASE (10 MS)
        STD     TOC3          	;AND STORE
	
	;LDAA	CNTR2*5
	;STAA	DATA2

	RTI
	