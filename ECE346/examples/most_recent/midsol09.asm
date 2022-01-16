********************************************************************************************************************************************
* Old Dominion University
* ECE 346 - MICROCONTROLLERS
* Spring 2009
*
* Midterm Solution by Filip Cuckov
* Last edit: March 15, 2009
*
* Description of program: 
* This is a user configurable counter program which gets input from the user from the console
* and ouputs a counter value onto PortB to a 7-segment display
*	
* The program accepts the following inputs:
*						Q : Quit program
*						+ : Count up
*						- : Count down
*						D : Count in decimal (output on the 7-segment is decimal)
*						H : Count in hexadecimal (output on the 7-segment is hexadecimal)
*						E : Enable counter (begin/resume counting)
*						P : Pause counter (turns on the decimal point on the 7-segment display)
*						N : Normal speed counting (counter increments once a second)
*						F : Fast speed counting (counter increments every half a second)
*						S : Stride (increment) of the counter
*							Once S is entered by the user, the program expects another input from 0-F (ASCII) 
*						R : Reset counter
*							On reset, the counter following are the values of the inputs: +,H,P,N,S1
********************************************************************************************************************************************

* -----------------------------------  EQUATES -----------------------------------

DATA    	EQU     $0	; Start of data segment
MAIN    	EQU     $100	; Start of code segment

OUTA		EQU	$FFB8
OUTSTRG 	EQU	$FFC7
INCHAR		EQU 	$FFCD
PORTB		EQU	$1004

*OC_3 Interrupt manipulation equates

TCNT    	EQU     $100E	; Free running counter
TOC3    	EQU     $101A	; Output compare register

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


* -----------------------------------  ORGs  -----------------------------------
	
*- Using FCBs to specify the interrupt specifics and thus save some memory from the main program

	ORG OC3_VEC
	
	FCB $7E		;Jump opcode
	FCB $01		;the location of your OC_3 service routine in memory
	FCB $96

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

* -----------------------------------  MAIN -----------------------------------

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
*        ADDD    #TIM_BASE      		;ADD some offset (anything large enough will do) CAN AVOID DOING THIS
*        STD     TOC3            	;SET UP  TOC3
	
      

*--------------- End Interrupt initialization ------------------------


INP_R	CLR	HEXDEC
	CLR	COUNTER
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
	DECA
	DECA
	BEQ	INP_H			; H
	SUBA	#6			
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
	BRA	INPUT

INP_H	CLR	HEXDEC
	BRA	INPUT

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

	CMPA	#'F'		;SEE IF INPUT IS ABOVE F
	BGT	INPUT		;IF SO IGNORE				CHECK INPUT FOR ALLOWABLE RANGE
	CMPA	#'0'		;SEE IF INPUT IS BELOW 0
	BMI	INPUT		;IF SO IGNORE
			
				;NOTE THAT IF THE INPUT IS STILL : OR ; OR < OR = OR < OR ? OR @ THEN IT WILL BE ACCEPTED (BUG)
				;BUT THEN AT THE END YOU WILL GET VALUES BETWEEN A (FOR :) AND F (FOR @)
				;SO NO MATTER WHAT YOU WILL GET A VALUE BETWEEN 0-F, SO THE PROGRAM WILL STILL WORK JUST FINE
	CMPA	#$40		
	BMI	N_0TO9		;NUMBER IS BETWEEN 0-9 NO NEED TO ADJUST
	SUBA	#7		;ADJUST FOR SPACES BETWEEN ASCII 9 AND ASCII A	

N_0TO9	SUBA 	#$30
	STAA 	STRIDE		
	BRA	INPUT

INP_Q	LDAA	#$FF
	STAA	PORTB
	SWI				;End of program



* -----------------------------------  INTERRUPT OC3 -----------------------------------

*-- OC3 INTERUPT SERVICE ROUTINE INTERRUPTS EVERY 20 MS 
        
OC3_SVC LDAA    #OC3F         ;SET UP TO 
        STAA    TFLG1         ;CLEAR OC3F		    MAKE SURE OC3 OCCURS EVERY 20ms
        LDD     TOC3          ;SET UP FOR NEXT INTERRUPT
        ADDD 	#TIM_BASE      ;ADD TIMEBASE 20 ms
        STD     TOC3          ;AND STORE 
*---------------------------------------------------------
	
	INC	STCNTR

	LDAA	STCNTR
	CMPA	P2S
	BEQ	P2SE		;CHECK THE TIME EXPIRED
	CMPA	P4S
	BEQ	P4SE
	CMPA	REMAIN
	BEQ	REMAINE
	BRA	OC3END

	
*0.2s Have expired				

P2SE	LDAB	TP
	BRA	OUTPUT

*0.4s Have expired
*Only if decimal then output
P4SE	
	LDAB	OP
	BRA	OUTPUT

*The remaining (0.1/0.6)s has expired
REMAINE	CLR	STCNTR		;RESET THE STATE COUNTER

	LDAB	STRIDE
	ADDB 	COUNTER		;INCREMENT/DECREMENT COUNTER BY STRIDE
	STAB	COUNTER	

	LDAA	HEXDEC		;CHECK IF IN HEX OR DECIMAL MODE
	BEQ	HEXOUT

*- NEED TO DO HEX TO DECIMAL CONVERSION HERE
	
	;AccB CONTAINS THE COUNTER VALUE
DECOUT	CLRA
	LDX	#100	
	IDIV		;DIVIDE BY 100, RESULT IN X, REMAINDER IN D
	XGDX		;REMAINDER IS IN X NOW
	STAB	HP	;FD IS THE FIRST DIGIT	

	XGDX	;REMAINDER IS IN D NOW

	LDX	#10
	IDIV		;DIVIDE BY 10, RESULT IN X, REMAINDER IN D
	XGDX		;REMAINDER IS IN X NOW
	STAB	TP	;SD IS THE SECOND DIGIT		

	XGDX	;REMAINDER IS IN D NOW

	STAB	OP	;TD IS THE THIRD DIGIT

* - DONE WITH HEX TO DECIMAL

*OUTPUT THIRD DIGIT SINCE WE ARE DEALING WITH DECIMAL
OUTTP	LDAB	HP
	BNE	OUTPUT		;ONLY IF IT IS ZERO (FOR NICER LOOKS)
	BRA	OC3END


	;AccB CONTAINS THE COUNTER VALUE
HEXOUT	ANDB	#$0F
	STAB	OP

	LDAB	COUNTER			

	LSRB
	LSRB
	LSRB
	LSRB

	STAB	TP		

OC3END	RTI     ;EXIT


* -----------------------------------  OUTPUT SUBROUTINE -----------------------------------

OUTPUT	LDY	#D7SEG		;ROUTINE ASSUMES THAT ACCB CONTAINS THE CORRECT VALUE TO BE OUTPUTTED
	ABY
	LDAB	0, Y
	STAB	PORTB
	BRA	OC3END	
