DATA    	EQU     $0	; Start of data segment
MAIN    	EQU     $100	; Start of code segment


OUTA		EQU	$FFB8
OUTSTRG 	EQU	$FFC7
INCHAR		EQU 	$FFCD
PORTB		EQU	$1004

*OC_3 Interrupt manipulation equates

TCNT    	EQU     $100E	; Free running counter
TOC3    	EQU     $101A	; Output compare register

TCTL1   	EQU     $1020
TMSK1  		EQU     $1022
TFLG1   	EQU     $1023

OC3_VEC 	EQU     $00D9	; pseudo-vector location for OC3 

OC3F    	EQU     %00100000 ;
OC3I    	EQU     %00100000 ;

*Time base and derivatives

TIM_BASE 	EQU     40000      ;0.5 us * 40000 = 20 ms = 0.02 s
P1S		EQU	5
*P2S		EQU     10         ;0.2 s = TIM_BASE * 10
*P4S		EQU	20	   ;0.4 s = TIM_BASE * 20
P5S	 	EQU     25	   ;0.5 s = TIM_BASE * 25
P6S		EQU	30
ONESEC		EQU	50	   ;1   s = TIM_BASE * 50

	
*- Using FCBs to specify the interrupt specifics and thus save some memory from the main program

	ORG OC3_VEC
	
	FCB $7E		;Jump opcode
	FCB $01		;the location of your OC_3 service routine in memory
	FCB $84

*- End Interrupt specification

        ORG DATA
*- Specifying data and their intial values

HEXDEC	FCB 0

HP	FCB 0
TP	FCB 0
OP	FCB 0

D7SEG 	FCB $C0, $CF, $92, $86, $8D, $A4, $A0, $CE,$80, $8C, $88, $A1, $F0, $83, $B0, $B8

PROMPT	FCB ">"
	FCB $04

COUNTER	FCB 0				; Counter is initially zero
STRIDE	FCB 1				; Stride is initially one

STCNTR	FCB 0

P2S	FCB 10
P4S	FCB 20
REMAIN	FCB 50


OUTVAL	RMB 1

DIG	FCB 0

OVRFLW	FCB 0


	ORG MAIN

*---------------- Interrupt initialization ----------------------------
*- To save space we can move this section in the data definition section
*- Note that in order to set the pseudo-vector to point to your OC_3 service
*- subroutine you need to know the exact location of the subroutine and to
*- guarantee that it will not change

*JUMP TABLE INITIALIZATION
*        LDAA    #$7E            	;SET UP OC3 JUMP TABLE    
*        STAA    OC3_VEC         	;FOR JMP
*        LDD     #OC3_SVC        	; TO
*        STD      OC3_VEC+1       	;OC3 INTERRUPT SERVICE

*OC3 INTERRUPT INITIALIZATION        
        LDAA    #OC3I           	;SET UP OC3 INTERRUPT
        STAA    TMSK1           	;ENABLE OC3 IN MASK REGISTER
        LDAA    #OC3F           	;SET UP TO CLEAR
        STAA    TFLG1           	;OC3  INTERRUPT FLAG

*TOC3 INITIALIZATION   
     
*        LDD     TCNT            	;GET CURRNT VALUE OF TCNT
*        ADDD    #TIM_BASE      		;ADD some offset (anything large enough will do)		CAN AVOID DOING THIS
*        STD     TOC3            	;SET UP  TOC3
	
      

*--------------- End Interrupt initialization ------------------------

		

INP_R	CLR	HEXDEC
R_D	CLR	COUNTER
	LDAA	#1
	STAA	STRIDE	
	LDAA	#50
	STAA	REMAIN
	LDAA 	#$40			; OUTPUT A 0 WITH THE DP ON, TO PORTB
	STAA 	PORTB
	
PAUSE	SEI
	
INPUT	LDX	#PROMPT			;OUTPUT THE PROMPT
	JSR	OUTSTRG
	JSR	INCHAR			;GET CHARACTER FROM USER
	
*	CMPA	#'Q'			;IF = Q THEN EXIT PROGRAM
*	BEQ	ENDP
*	CMPA	#'+'
*	BEQ	INP_PL
*	CMPA	#'-'
*	BEQ	INP_PL
*	CMPA	#'D'
*	BEQ	INP_D
*	CMPA	#'H'
*	BEQ	INP_H
*	CMPA	#'R'
*	BEQ	INP_R
*	CMPA	#'S'
*	BEQ	INP_S			; A NORMAL WAY TO DO A MENU.
*	CMPA	#'E'
*	BEQ	INP_E
*	CMPA	#'P'
*	BEQ	INP_P			; BELOW IS A SPACE SAVER OPTION
*	CMPA	#'N'
*	BEQ	INP_N			; BY RECOGNIZING THAT DEF AND PQRS ARE CONSECUTIVELY PLACED IN THE ASCII TABLE 
*	CMPA	#'F'			; CAN ELIMINATE USING A LOT OF BYTES, SIMPLY BY USING CONSECUTIVE DECREMENTS (1 BYTE OP)
*	BEQ	INP_F

	SUBA	#'+'			; +
	BEQ	INP_PL
	DECA
	DECA
	BEQ	INP_PL			; -
	SUBA	#23
	BEQ	INP_D			; D			
	DECA
	BEQ	INP_E			; E
	DECA							
	BEQ	INP_F			; F		
	SUBA	#8			
	BEQ	INP_N			; N
	DECA
	DECA	
	BEQ	INP_P			; P
	DECA
	BEQ	INP_Q			; Q
	DECA				
	BEQ	INP_R			; R
	DECA	
	BEQ	INP_S			; S			


	BRA	INPUT			;If no match -> GET INPUT AGAIN


INP_PL	NEG	STRIDE
	BRA	INPUT	

INP_D	INC	HEXDEC
	BRA	R_D

INP_H	CLR	HEXDEC
	BRA	INP_R

INP_E	CLI                     	;ENABLE SYSTEM INTERRUPT
	BRA	INPUT

INP_P	LDAA	PORTB			;DISABLE SYSTEM INTERRUPT
	ANDA	#%01111111		;TURN ON DP
	STAA	PORTB
	BRA	PAUSE

INP_N	LDAA	#50
	BRA	STNF			;RECOGNIZED A SAME ACTION AS FOR FAST MODE SO MADE A BRANCH, SAVED SOME BYTES

INP_F	LDAA	#25

STNF	STAA	REMAIN
	CLR	STCNTR
	BRA	INPUT

INP_S	JSR	INCHAR		;get the stride value (0-F) (which means that the input should be less than $46 (F-ascii) and greater than $30 (0-ascii)
	LDAB	STRIDE
	SUBA 	#$30
	STAA 	STRIDE
	BGT	STSTR		;if > 0, 	
	NEGA

STSTR	STAA	STRIDE		;you can do checking here or just assume the correct value (currently assuming correct value)
	BRA	INPUT

INP_Q	SWI				;End of program



* OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 20 MS 
        
OC3_SVC LDAA    #OC3F         ;SET UP TO 
        STAA    TFLG1         ;CLEAR OC3F
        LDD     TOC3          ;SET UP FOR NEXT INTERRUPT
        ADDD 	#TIM_BASE      ;ADD TIMEBASE 20 ms
        STD     TOC3          ;AND STORE 
*-----------------------------
	
	INC	STCNTR

	LDAA	STCNTR
	CMPA	#1
	BEQ	BEGIN
	CMPA	P2S
	BEQ	P2SE
	CMPA	P4S
	BEQ	P4SE
	CMPA	REMAIN
	BEQ	REMAINE
	BRA	OC3END

*Counter just begun
*Output 1st digit (ONLY IF DECIMAL)
BEGIN	LDAB	HP
	BNE	OUTPUT
	BRA	OC3END		
	
*0.2s Have expired				
*Output 2nd digit
P2SE	LDAB	TP
	BRA	OUTPUT

*0.4s Have expired
*Output 3rd digit 
P4SE	LDAB	OP
	BRA	OUTPUT

*The remaining (0.1/0.6)s Have expired
REMAINE	LDAA	STRIDE
	ADDA 	COUNTER
	
*----------------------- DECIMAL ADJUSTMENT
	LDAB	HEXDEC	
	BEQ	OUTADJ		; IF HEX NO NEED TO ADJUST
	
	DAA			;DECIMAL ADJUSTMENT
	
	BCC	OUTADJ		;CARRY SET ON DECIMAL ADJUST IF NUMBER EXCEEDS 99 
	
INCHP	INC	HP		;IF CARRY IS SET THEN INCREASE THE HP POINTER

CLCNTR	CLR	COUNTER		;AND CLEAR THE DECIMAL ADJUSTED COUNTER
*----------------------- DONE WITH DECIMAL ADJUSTMENT

OUTADJ	STAA	COUNTER	
*	STAA	OVRFLW		;OVERFLOW CHECK
	ANDA	#$0F
	STAA	OP

	LDAA	COUNTER			

	LSRA
	LSRA
	LSRA
	LSRA

	STAA	TP

	CLR	STCNTR		



OC3END	RTI     ;EXIT


OUTPUT	LDY	#D7SEG
	ABY
	LDAB	0, Y
	STAB	PORTB
	BRA	OC3END	

