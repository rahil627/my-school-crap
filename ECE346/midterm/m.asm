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
ONE_CNT		EQU     12	;25ms  *  12  = .3s	.15	
ZERO_CNT 	EQU     28      ;25ms  *  28  = .7s	.35
TIME_BASE 	EQU     50000	;0.5us *50000 = 25ms
TIME_BASE_FAST 	EQU     25000	;0.5us *25000 = 12.5ms	time_base_fast=time_base/2
OFFSET		EQU     10000	;0.5us *10000 =  5ms

        ORG DATA	;initialize data
CNTR    	RMB     1	;time base counter
STATE		RMB	1	;interrupt state variable

NUM		FCB	$00, $01, $02, $03, $04, $05, $06, $07, $08, $09, $0A, $0B, $0C, $0D, $0E, $0F ;used to compare to count, increments/decerements the same time as HEX does, so they are always together
HEX		FCB 	$C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8 ;hex value of displayed characters
COUNT   	FCB	$00
;COUNT2		FCB	$00	;first nibble
STRIDE		FCB	$00

;booleans			 0 = default	1 = user changed
DOWN		FCB	$00	;0 = count up	1 = count down
;PAUSE		FCB	$00	;0 = not paused	1 = pause
FAST		FCB	$00	;0 = normal	1=fast

	ORG MAIN
*DATA INITIALIZATION (clear state and counter values)
	LDAA	#0	;load 0 into accA
	STAA	STATE	;state=0
	STAA	CNTR	;cntr=0

	LDX 	#NUM	;points to the beginning of NUM
	LDY 	#HEX	;points to the beginning of DAT2

*---------------- Interrupt initialization ----------------------------

*JUMP TABLE INITIALIZATION
        LDAA    #$7E            ;SET UP OC3 JUMP TABLE    
        STAA    OC3_VEC         ;FOR JMP
        LDD     #OC3_SVC        ; TO
        STD      OC3_VEC+1      ;OC3 INTERRUPT SERVICE

*OC3 INTERRUPT INITIALIZATION        
        LDAA    #OC3I           ;SET UP OC3 INTERRUPT
        STAA    TMSK1           ;ENABLE OC3 IN MASK REGISTER
        LDAA    #OC3F           ;SET UP TO CLEAR
        STAA    TFLG1           ;OC3  INTERRUPT FLAG

*TOC3 INITIALIZATION        
        LDD     TCNT            ;GET CURRNT VALUE OF TCNT
        ADDD   #OFFSET         	;ADD 5 MS OFFSET
        STD     TOC3            ;SET UP  TOC3

        CLI                     ;ENABLE SYSTEM INTERRUPT
*--------------- End Interrupt initialization ------------------------

COUNT_UP	;LDAB	#0
		STAB	DOWN
		;BRA	INPUT	;to save a byte

;loops through this constantly checking for inchar
INPUT	JSR	INCHAR

	;turn off a setting
	LDAB 	#0

	CMPA	#'N'
	BEQ	NORMAL_MODE
	
	CMPA	#'+'
	BEQ	COUNT_UP

	CMPA	#'P'
	BEQ 	PAUSE

	CMPA	#'R'
	BEQ	RESET

	;turn on a setting
	LDAB 	#1	

	CMPA	#'F'
	BEQ	FAST_MODE

	CMPA	#'-'
	BEQ	COUNT_DOWN

	CMPA	#'E'
	BEQ	UNPAUSE

	;other
	CMPA	#'S'
	BEQ	CHANGE_STRIDE

	;CMPA	#'D'
	;BEQ	ENDP

	;CMPA	#'H'
	;BEQ	ENDP

	CMPA	#'Q'
	BEQ	ENDP

	BRA	INPUT	;loop

;functions
ENDP	SWI

RESET		;LDAB	#0		;count=0
		STAB	COUNT
		LDAB	#1		;stride=1
		STAB	STRIDE
		BRA	INPUT

COUNT_DOWN	;LDAB	#1		;down=1
		STAB	DOWN
		BRA	INPUT

FAST_MODE	;LDAB	#1		;fast=1
		STAB	FAST
		BRA	INPUT

NORMAL_MODE	;LDAB	#0		;fast=0
		STAB	FAST
		BRA	INPUT

PAUSE		;LDAB	#0		;stride=0
		STAB	STRIDE
		;count + DP ...how to get this hex?
		;then need to revert back to count
		BRA	INPUT

UNPAUSE		;LDAB	#1		;stride=1
		STAB	STRIDE
		BRA	INPUT

CHANGE_STRIDE	JSR	INCHAR
		SUBA	#$30		;ASCII->HEX conversion
		;conditionals?
		STAA	STRIDE		;Store inputted character in hex into Strid function
		BRA	INPUT		;Go back to INPUT function


* OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 10 MS AND 
* DECREMENTS A COUNTER PROVIDES A TIME BASE EVENT EVERY 10 MS
OC3_SVC LDAA   #OC3F		;SET UP TO 
	STAA    TFLG1		;CLEAR OC3F
	LDD     TOC3		;SET UP FOR NEXT INTERRUPT

	LDAB	#1
	CMPB	FAST
	BEQ	ADDD_F
	;BNE	ADDD_N

ADDD_N	ADDD   #TIME_BASE	;add time_base
	STD     TOC3		;and store it
	BRA	LDSTATE

ADDD_F	ADDD   #TIME_BASE_FAST	;add time_base_fast
	STD     TOC3		;and store it

LDSTATE	LDAA	STATE		;load state
	CMPA	#1		;branch to that state		
	BEQ	ONE
	;BNE	ZERO
 
ZERO	LDAA 	CNTR		;if(cntr!=0)exit interrupt
	BNE	OC3END
	
        LDAA    #ONE_CNT	;cntr=one_cnt
        STAA    CNTR
	
	LDAA	#1		;state=1 (set next state)
	STAA	STATE

	LDAA	COUNT		;Load Counter

	LDAB	DOWN		;if(down==1){branch to sub}
	CMPB	#1
	BEQ	SUB
	;BNE	ADD	

	;count+=stride
	ADDA	STRIDE		;a=count+stride
	BRA	MASK

SUB	SUBA	STRIDE		;a=count-stride

MASK	STAA	COUNT		;count=a (count+/-stride)
	ANDA	#%11110000	;mask lower bits

	LSRA			;Shift right 4x to get high bits to the right side
	LSRA
	LSRA
	LSRA

	BRA	COMP		;continue to comp/display

ONE     LDAA	CNTR				
	BNE	OC3END
        LDAA    #ZERO_CNT
        STAA    CNTR	
	LDAA	#0
	STAA	STATE

	LDAA	Count		;Load Counter
	ANDA	#%00001111	;mask higher bits

	BRA	COMP


;X and Y increment together, so it just loops together until it reaches count (taken from assignment2)
COMP	LDAB	0, X		;b=x
	CBA			;count==x? [compare b to a]
	BEQ	DISPLAY		;branch if they are equal each other [branch if equal to zero]
	INX			;x++
	INY			;y++
	BRA 	COMP		;branch to comp [branch always]

DISPLAY	LDAA	0, Y		;portb=hex
	STAA	PORTB
	;BRA 	OC3END

OC3END	DEC     CNTR		;cntr--
	RTI			;end interrupt



;decimal idea

	;hex-decimal conversion
;count=B4
;ldaa high_nibble	;11
;ldab #16		;16
;mul ab			;176[store in 16-bit/double]
;addd low_nibble	;180
;stad

	;display
;state0
;ANDD	#%0000000000001111	;16-bit mask
;DISPLAY D

;state1
;ANDD	#%0000000011110000	;16-bit mask
;DISPLAY D

;state2
;ANDD	#%0000111100000000	;16-bit mask
;DISPLAY D
