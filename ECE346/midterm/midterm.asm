OC3_VEC 	EQU     $00D9	; pseudo-vector location for OC3 	
DATA    	EQU     $0	; Start of data segment
MAIN    	EQU     $100	; Start of code segment
OUTA		EQU	$FFB8
INCHAR		EQU 	$FFCD

* 6811 special register definitions
PORTB   	EQU     $1004
PORTC   	EQU     $1003
PORTE   	EQU 	$100A
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
ONE_CNT		EQU     8          ;25 MS * 8 = .2 SEC
ZERO_CNT 	EQU     32         ;25 MS * 32 = .8 SEC
ONE_CNT2	EQU	4	   ;25 MS * 4 = .1 SEC
ZERO_CNT2	EQU	36	   ;25 MS * 36 = .9 SEC
TIM_BASE 	EQU     50000      ;0.5 US *20000 = 25 MS
OFFSET		EQU     10000      ;0.5us *10000 = 5 ms

        ORG DATA
CNTR    RMB     1               	;TIME BASE COUNTER 
STATE	RMB	1			;INTERRUPT STATE VARIABLE
CCOMP	FCB	$00, $01, $02, $03, $04, $05, $06, $07, $08, $09, $0A, $0B, $0C, $0D, $0E, $0F ; Data used to compare to counter
SHOWDAT	FCB 	$C0, $CF, $92, $86, $8D, $A4, $A0, $CE, $80, $8C, $88, $A1, $F0, $83, $B0, $B8 ; Hex value of displayed characters
Count   FCB	$00									       ; Count used in state ZERO and ONE
Strid	FCB	$00									       ; Used for STIDE fucntion
UPDOWN	FCB	$01									       ; Used for increase and decrease counting
FASTM	FCB	$00									       

	ORG MAIN
*DATA INITIALIZATION
	LDAA	#0			;CLEAR STATE AND COUNTER VALUES
	STAA	STATE
	STAA	CNTR


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
	LDX	#CCOMP			;X points to beginning of CCOMP Data
	LDY	#SHOWDAT		;Y points to beginning of SHOWDAT Data

INPUT	JSR	INCHAR			;GET CHARACTER FROM USER
	CMPA	#'Q'			;IF = Q THEN EXIT PROGRAM
	BEQ	ENDP

	CMPA	#'R'			;IF input is R go to RESET function
	BEQ	RESET
	
	CMPA	#'S'			;IF input is S go to STRIDE function
	BEQ	Stride

	CMPA	#'+'			;IF input is + go to INCR function
	BEQ	INCR

	CMPA	#'-'			;IF input is - go to DECR function
	BEQ	DECR

	CMPA	#'P'			;IF input is P go to PAUSE function
	BEQ 	PAUSE

	CMPA	#'E'			;IF input is E go to ENABLE function
	BEQ	ENABLE

	CMPA	#'N'			;IF input is N go to NORMAL function
	BEQ	NORMAL
	
	CMPA	#'F'			;IF input is F go to FAST function
	BEQ	FAST


	BRA	INPUT			;OTHERWISE GET INPUT AGAIN

ENDP	SWI

RESET	LDAA	#0		;Load 0 into A
	STAA	Count		;Store 0 into Count
	LDX	#CCOMP		;X points to beginning of CCOMP Data
	LDY	#SHOWDAT	;Y points to beginning of SHOWDAT Data
	BRA	INPUT		;Go back to INPUT function

Stride	JSR	INCHAR		;Get character from user
	SUBA	#$30		;Subtract $30 from inputted character to convert to hex
	STAA	Strid		;Store inputted character in hex into Strid function
	BRA	INPUT		;Go back to INPUT function

INCR	LDAA	#1		;Load 1 into A 
	STAA	UPDOWN		;Store 1 into UPDOWN for use in interrupt state ZERO
	BRA	INPUT		;Go back to INPUT function 

DECR	LDAA	#0		;Load 0 into A
	STAA	UPDOWN		;Store 0 into UPDOWN for use in interrupt state ZERO
	BRA	INPUT		;Go back to INPUT function

PAUSE	LDAA	#0		;Load 0 into A
	STAA	Strid		;Store 0 into Strid for use in interrupt state ZERO
	BRA	INPUT		;Go back to INPUT function

ENABLE	LDAA	#1		;Load 1 into A
	STAA	Strid		;Store 1 into Strid for use in interrupt state ZERO
	BRA	INPUT		;Go back to INPUT function

NORMAL	LDAA	#0		;Load 0 into A
	STAA	FASTM		;Store 0 into FASTM for use in interrupt state ZERO
	BRA 	INPUT		;Go back to INPUT function

FAST	LDAA	#1		;Load 1 into A
	STAA	FASTM		;Store 1 into FASTM for use in interrupt state ZERO
	BRA	INPUT		;Go back to INPUT function

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
	BEQ	ONE		;STATE - 1 = 0? Y-> GO TO STATE TWO	
				;N-> GO TO STATE ONE


*       TWO STATE MACHINE       
ZERO	LDAA	CNTR		;IF CNTR NOT = 0				
	BNE	OC3END		;EXIT INTERRUPT

	LDAA	FASTM		;Load A with FASTM value
	CMPA	#1		;Compare it to 1 and if true go to FASTMO function
	BEQ	FASTMO

	LDAA    #ONE_CNT        ;CNTR =  ONE_CNT 
        STAA    CNTR

	BRA	Here2		;Branch to Here2
FASTMO	LDAA	#ONE_CNT2	;Load Fast Mode 
	STAA	CNTR		;Store Fast Mode on CNTR

Here2	LDAA	#1		;SET NEXT STATE
	STAA	STATE

	LDAA	UPDOWN		;Load A with UPDOWN number
	CMPA	#0		;If UPDOWN number is 0 go to Decreasing function
	BEQ	SUBTR
		
	LDAA	Count		;Load Counter
	ADDA	Strid		;Add Strid to Count
	BRA	StoreC		;Branch to Store count to avoid section meant for decreasing
SUBTR	LDAA	Count		;Load Counter
	SUBA	Strid		;Subtract Strid from Count to start counting by decreasing
StoreC	STAA	Count		;Store new value of Strid and Count into Count
	ANDA	#%11110000	;Mask Count to put high bits in lower bit section

	LSRA			;Shift right to get high bits to the end, ie - $10 to $01
	LSRA
	LSRA
	LSRA	
	BSR	COMP		;Branch to subroutine COMP
		
	BRA 	OC3END

ONE     LDAA	CNTR		;IF CNTR NOT = 0				
	BNE	OC3END		;EXIT INTERRUPT

        LDAA    #ZERO_CNT  	;CNTR = ZERO_CNT
        STAA    CNTR	

	LDAA	#0		;SET NEXT STATE
	STAA	STATE

	LDAA	Count		;Load Counter
	ANDA	#%00001111
	BSR	COMP		;Branch to subroutine COMP
	
	BRA 	OC3END
	
OC3END	DEC     CNTR          ;DEC TIME BASE COUNTER
	RTI                   ;EXIT

COMP	LDAB	0, X		;Load NUM into B
	CBA			;Compare Counter to NUM
	BEQ	Display		;If equal go to Display Function
	INX			;Increment CCOMP
	INY			;Increment SHOWDAT
	BRA 	COMP		;Loop back to COMP functiond

Display	LDAA	0, Y		;Load SHOWDAT for displaying correct number of 7-segment	
	STAA	PORTB		;Display on 7-segment
	RTS
