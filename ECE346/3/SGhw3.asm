
*EQUATES:
DATA    EQU     $00
MAIN    EQU     $100
OC1_VEC EQU     $DF
OC3_VEC	EQU	$00D9
TCNT    EQU     $100E
TOC1    EQU     $1016
TOC2    EQU     $1018
TOC3    EQU     $101A
OC1M    EQU     $100C
OC1D    EQU     $100D
TCTL1   EQU     $1020
TCTL2	EQU	$1021
TMSK1   EQU     $1022
TFLG1   EQU     $1023
OC1F    EQU     %10000000 ; used to clear OC2 in PWM initalization
OC1I    EQU     %10000000 ; used to turn on TOC1 in OC1 Interrupt initalization
OC3I    EQU     %00100000
OC3F    EQU     %00100000

ONE_CNT  EQU      50   ;20 MS * 50 = 1 SEC
TWO_CNT  EQU      100   ;20 MS * 100 = 2 SEC
TIM_BASE EQU    40000   ;0.5 US *40000 = 20 MS
TIM_BASE2 EQU    20000   ;0.5 US *20000 = 10 MS
T_90     EQU    16000   ;80% duty cycle @(T=10MS) = 9 ms = 18000
T_30     EQU     6000
test 	 EQU	 2000
;count	 EQU 	   70	; 70 * 200 = 1400 , so with test starting at 2000 + 1400 = 1600 which equals 4V and where it stops
OFFSET   EQU     1000   ;.5 ms offset

		ORG  DATA
CNTR     	RMB     1   ;TIME BASE COUNTER (MESSAGE)
CNT_DN		RMB	1
PULS_WIDTH 	RMB  	2   ;PULSEWIDTH/TIMEBASE = DUTYCYCLE(MESSAGE)

	ORG MAIN
	
	LDD #2000
	STD $00FA

	;LDD	#2000
	;STD	Count

*JUMP TABLE INITIALIZAITON
        LDAA    #$7E            ;SET UP OC1 JUMP TABLE    
        STAA    OC1_VEC         ;FOR JMP
	STAA	OC3_VEC		;@Moved this up here

        LDD     #OC1_SVC        ; TO
        STD     OC1_VEC+1       ;OC1 INTERRUPT SERVICE

*	STAA	OC3_VEC		;@ERROR moved it up
	LDD	#OC3_SVC
	STD	OC3_VEC+1
*PWM INITIALIZATION   
        LDAA    #%01000000      ; SET UP OC1M 
        STAA    OC1M            ;AND OC1D TO PRODUCE  A 1 ON PA_6 (OC2)
        STAA    OC1D            ;AND 
        LDAA    #OC1F	        ;SET UP OC2 TO CLEAR (PA_6)
        STAA    TCTL1           ;ON TOC2 COMPARE
*TOC1 INITIALIZATION        
        LDD     TCNT            ;GET CURRENT VALUE OF TCNT
        ADDD    #OFFSET        ;ADD .5 MS OFFSET TO
        STD     TOC1            ;SET UP  TOC1 FOR FIRST INTERRUPT

*TOC3 INITIALIZATION        
        LDD     TCNT            ;GET CURRENT VALUE OF TCNT
        ADDD    #OFFSET        ;ADD .5 MS OFFSET TO
        STD     TOC3            ;SET UP  TOC1 FOR FIRST INTERRUPT

*OC1 INTERRUPT INITIALIZATION        
        LDAA    #OC1I           ;SET UP OC1 INTERRUPT
	ORAA	#OC3I		; @ This way you get %10100000
        STAA    TMSK1           ;ENABLE OC1 IN MASK REGISTER

* @ you can avoid doing these 2 below
*        LDAA    #OC1F           ;SET UP TO CLEAR
*	ORAA	#OC3F		; @ This way you get %10100000
        STAA    TFLG1           ;OC1  INTERRUPT FLAG


* @error you are disabling OC1 by doing this
*OC3 INTERRUPT INITIALIZATION        
*        LDAA    #OC3I           	;SET UP OC3 INTERRUPT
*       STAA    TMSK1           	;ENABLE OC3 IN MASK REGISTER
*        LDAA    #OC3F           	;SET UP TO CLEAR
*        STAA    TFLG1           	;OC3  INTERRUPT FLAG
        
	CLI                     ;ENABLE SYSTEM INTERRUPT

*OC1  JMP Table
*TMSK1 ß %10000000   Set for OC1
*TFLG1 ß %10000000
*TCTL1 ß %10000000   Clear OC2
*OC1M  ß %01000000   Enable OC2
*OC1D  ß %01000000   Data on OC2

* @ Code below tries to mimic the flowchart from the lectures but is incorrect. Check your branch locations! 


repeat	LDAA	#0	;initalize cntr
	STAA	CNTR

	LDAA	#CNTR	;check if equal to 50 and if so begin to dec cntr
	CMPA	#50
	BEQ	countd

	BRA	here

countd	LDAA	#1	;CNT_DN is used in OC3_SCV to determine count up or down
	STAA	CNT_DN

here	LDAA	#CNTR 	;check if equal to 0 and if so begin to inc cntr
	CMPA	#0
	BEQ	countup

countup	LDAA	#0
	STAA	CNT_DN
	
	BRA	repeat

*
*       TWO STATE MACHINE       
*
;STATE1  
	;LDD     #T_30           ;SET UP FOR 90% DC
        ;STD     PULS_WIDTH      ;
	
	;LDD	PULS_WIDTH
	;ADDD	$FF
	;STD	PULS_WIDTH
	;STAB	CNTR2
	;CMPB	#30
	;BEQ	here

	;LDAB	#70
	;LDAA	$00FA
	;ABA
	;STAA	$00FA
	;LDD	$00FA

	;LDD	PULS_WIDTH
	;ADDD	$FF
	;STD	PULS_WIDTH
	;INC	CNTR2

;here    LDAA    #TWO_CNT        ;SET UP 2 SECOND DELAY 
       ; STAA    CNTR
   
;LOOP1	;LDAB	#70
	;LDAA	$00FA
	;ABA
	;STAA	$00FA
	;LDD	$00FA

	;STD	PULS_WIDTH

	;TST     CNTR            ;CHECK COUNTER
       ; BNE     LOOP1           ;WAIT FOR ZERO COUNT
        
;STATE0  
	;LDD     #T_90           ;SET UP FOR 30%VDC
     ;   STD     PULS_WIDTH
     ;   LDAA    #ONE_CNT       ;SET UP 1 SEC DELAY
      ;  STAA    CNTRn

;LOOP0   TST     CNTR            ;CHECK COUNTER
       ; BNE     LOOP0           ; WAIT FOR ZERO COUNT
       ; BRA     STATE1          ;ELSE START OVER -ENDLESS LOOP

*        
*       OC1 INTERUPPT SERVICE ROUTINE
*       INTERRUPTS EVERY 10 MS AND DECREMENTS A COUNTER
*       PROVIDES A TIME BASE EVENT EVERY 10 MS
*       SETUPS TOC3 FOR COMPARE AFTER (PUL_WIDTH) TIME UNITS 
*
*        
OC1_SVC LDAA    #OC1F           ;SET UP TO     
*	STAA    TFLG1           ;CLEAR OC1F	@Can't do this 

	LDAA #OC1F
	ORAA TFLG1		; @ this way you do not interfere with OC3 ( do the opposite in OC3!)
	STAA TFLG1		

	;PULS_WIDTH = 2000 + (16,000 - 4,000)/255 * DATA
	;PULS_WIDTH = 2,000 + 47 * DATA
	;DATA = 255/100 * CNTR, Data = 2 * CNTR
	;MUL Multiply 8 by 8 A * B = D

	;BSR	CNTRadd ;goes to subrutine for cacluating DATA

	LDAA	#47
	LDAB	#$00FD	;@ ????? you are loading a 16 bit value in an 8 bit register?
	MUL		;Muliples A and B and puts in D
	STD	$00F8 ;47 * DATA = $00F8

	LDD	$00F8
	ADDD	$00FA ; 2000 + (DATA with 47 multipled)
	STD	PULS_WIDTH

        LDD     PULS_WIDTH      ; SET UP PULSE WIDTH
        ADDD    TOC1            ;ADD CURRENT LOCATION
        STD     TOC2            ;AND STORE IN TOC2
        LDD     TOC1            ;SET UP FOR NEXT INTERRUPT
        ADDD    #TIM_BASE       ;ADD TIMEBASE (10 MS)
        STD     TOC1            ;AND STORE 
        DEC     CNTR            ;DEC TIME BASE COUNTER
        RTI                     ;EXIT

OC3_SVC LDAA    #OC3F         ;SET UP TO 
        STAA    TFLG1         ;CLEAR OC3F

        LDD     TOC3          ;SET UP FOR NEXT INTERRUPT
        ADDD   #TIM_BASE      ;ADD TIMEBASE (10 MS)?
        STD     TOC3          ;AND STORE 
	
	LDAA	CNT_DN		; IF CNT_DN = 1 count down , if 0 count up
	CMPA	#1
	BEQ	TRUE
	CMPA	#0
	BEQ	FALSE


TRUE    DEC     CNTR          ;DEC TIME BASE COUNTER
	BSR	CNTRadd
	BRA	toRTI

FALSE	INC	CNTR
	BSR	CNTRadd

toRTI   RTI                   ;EXIT

CNTRadd	LDAA	#CNTR
	LDAB	#2
	MUL
	STD	$00FD  ;2 * CNTR = $00FD
	RTS

