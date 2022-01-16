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
ZERO_CNT 	EQU     24      ;25ms  *  24 = .6s
TIM_BASE 	EQU     50000	;0.5us *50000 = 25ms
TIM_BASE_FAST 	EQU     25000	;0.5us *25000 = 12.5ms
OFFSET		EQU     10000	;0.5us *10000 =  5ms

        ORG DATA	;initialize data
CNTR    	RMB     1	;time base counter
STATE		RMB	1	;interrupt state variable
NUM		FCB	$00, $01, $02, $03, $04, $05, $06, $07, $08, $09, $0A, $0B, $0C, $0D, $0E, $0F ;used to compare?
HEX		FCB 	$C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8 ;hex value of displayed characters
COUNT   	FCB	$00	;second nibble
COUNT2		FCB	$00	;first nibble
DOWN		FCB	$00	;0 = count up	1 = count down
PAUSE		FCB	$00	;0 = not paused	1 = pause
FAST		FCB	$00	;0 = normal	1=fast
;15INHEX	FCB	$0F

	ORG MAIN
*DATA INITIALIZATION (clear state and counter values)
	LDAA	#0	;load 0 into accA
	STAA	STATE	;state=0
	STAA	CNTR	;cntr=0
	STAA	COUNT	;count=0
	STAA	COUNT2

	LDX 	#NUM		;points to the beginning of NUM
	LDY 	#HEX		;points to the beginning of DAT2

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

COUNT_UP	;LDAA	#0
		STAB	DOWN
		;BRA	INPUT

INPUT	JSR	INCHAR	;get char from user, when it does it goes through this list

	;LDAA 	#0	;not working properly

	;turn off a setting
	LDAB 	#0

	CMPA	#'N'
	BEQ	NORMAL_MODE
	
	CMPA	#'+'
	BEQ	COUNT_UP

	CMPA	#'R'
	BEQ	RESET

	;turn on a setting
	LDAB 	#1	

	CMPA	#'-'
	BEQ	COUNT_DOWN

	;CMPA	#'D'
	;BEQ	ENDP

	;CMPA	#'H'
	;BEQ	ENDP

	;CMPA	#'S'
	;BEQ	ENDP

	;CMPA	#'E'
	;BEQ	ENABLE_PAUSE

	CMPA	#'F'
	BEQ	FAST_MODE

	CMPA	#'Q'
	BEQ	ENDP

	BRA	INPUT	;loop

ENDP	SWI


;1
COUNT_DOWN	;LDAB	#1
		STAB	DOWN
		BRA	INPUT

;ENABLE_PAUSE	;LDAB	#1
		;STAB	PAUSE
		;BRA	INPUT

FAST_MODE	;LDAB	#1
		STAB	FAST
		BRA	INPUT

;0
NORMAL_MODE	;LDAA	#0
		STAB	FAST
		BRA	INPUT

RESET		STAB	COUNT
		STAB	COUNT2
		BRA	INPUT


;code goes here?

* OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 10 MS AND 
* DECREMENTS A COUNTER PROVIDES A TIME BASE EVENT EVERY 10 MS
*        
OC3_SVC LDAA   #OC3F		;SET UP TO 
	STAA    TFLG1		;CLEAR OC3F
	LDD     TOC3		;SET UP FOR NEXT INTERRUPT

	LDAB	#1
	CMPB	FAST
	BEQ	ADDD_F
	;BNE	ADDD_N

ADDD_N	ADDD   #TIM_BASE	;ADD TIMEBASE (10 MS)?
	STD     TOC3		;AND STORE
	BRA	LD_STAT

ADDD_F	ADDD   #TIM_BASE_FAST
	STD     TOC3		;AND STORE

LD_STAT	LDAA	STATE		;LOAD STATE
	CMPA	#1		
	BEQ	ONE		;STATE - 1 = 0? Y-> GO TO STATE TWO	
				;N-> GO TO STATE ONE
   
ZERO	LDAA 	CNTR		;if (CNTR != 0)
	BNE	OC3END		;exit interrupt
	
        LDAA    #ONE_CNT	;CNTR = ONE_CNT 
        STAA    CNTR
	
	LDAA	#1		;SET NEXT STATE (STATE=1)
	STAA	STATE
	
	LDAA	COUNT		;a=count

COMP	LDAB	0, X		;b=x
	CBA			;count==x? [compare b to a]
	BEQ	DISPLAY		;branch if they do not equal each other [branch if equal to zero]

	INX			;x++
	INY			;y++

	BRA 	COMP		;branch to comp [branch always]

DISPLAY			LDAB	#0		;if(state==0){branch to state0} else branch to state1
			CMPB	STATE
			BNE	STATE1
			;BEQ	STATE0

STATE0			LDAB	#1		;if(down==1){branch to check_reset_down} else branch to check_reset_up
			CMPB	DOWN
			BEQ	CHECK_RESET_DOWN
			;BNE	CHECK_RESET_UP

CHECK_RESET_UP		INC	COUNT
CHECK_RESET_UP2		LDAA 	0, Y
			STAA	PORTB	;portb=y (y=hex)
			LDAB	#16	;if(count==16) reset_up
			CMPB	COUNT		;
			BEQ	RESET_UP
			BNE	OC3END

STATE1			LDAB	#1		;if(down==1){branch to check_reset_down2} else branch to check_reset_up2
			CMPB	DOWN
			BEQ	CHECK_RESET_DOWN2
			BNE	CHECK_RESET_UP2

CHECK_RESET_DOWN	DEC	COUNT
CHECK_RESET_DOWN2	LDAA 	0, Y	;portb=y (y=hex)
			STAA	PORTB

		 	LDAB	#0 	;if(count==0) reset_down
			CMPB	COUNT
			BEQ	RESET_DOWN
			BNE	OC3END

RESET_UP		LDAB	#0	;count=0
			STAB	COUNT
			INC	COUNT2	;count2++
			BRA	OC3END

RESET_DOWN		LDAB	#15	;count=F
			STAB	COUNT
			DEC	COUNT2	;count2++
			;BRA	OC3END

OC3END	DEC     CNTR		;cntr--
	RTI	

ONE     LDAA	CNTR
	BNE	OC3END	

        LDAA    #ZERO_CNT  	;cntr=#zero_cnt
        STAA    CNTR
	
	LDAA	#0		;set next state (state=0)
	STAA	STATE

	LDAA	COUNT2
	BRA	COMP
