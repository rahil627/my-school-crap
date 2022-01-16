*	INTERRUPT
*	CNTR IS NUMBER OF TIMEBASES ELAPSED (DECREMENTED)
*	IF INPUT IS -, THEN: NEG STRIDE

	LDAA 	CNTR		;IF CNTR NOT = 0
	BNE	OC3END		;EXIT INTERRUPT

	LDAA 	STATE 		;Branches based on state
	BNE 	STATEONE

STATEZERO LDAA #20	;Makes the interrupt 0.2seconds, increments the state
	  STAA CNTR
	  INC STATE
	
GETDIG	LDAA #0		;Separates the upper and lower bits of the counter via division
	LDAB VALUE
	LDX #$0010
	IDIV
	STAB LB
	XGDX
	LDX #$0031
	BRA COMP
	
*---------------------------
*	LDAA VALUE
*
*
*	ANDA #$0F
*
*	LSRA
*	LSRA
*	LSRA
*	LSRA
*--------------------------
	

STATEONE LDAA SPDMODE	;Loads the time based on the speed of display, either 0.3 or 0.8 seconds
	 CMPA FST
	 BEQ FAST
	 LDAA #80	;Times the interrupt in normal mode
	 BRA FAST1 
	
FAST	LDAA #30	;Times the interrupt in fast mode
FAST1	STAA CNTR
	DEC STATE
		
COUNTER	LDX #$0031	
	LDAA PEMODE	;Checks whether paused or enabled
	CMPA PAUSED
	BEQ LOADVAL	;If paused, skips incrementing/decrementing the counter

ADJCNT	LDAA VALUE	;Increments the counter
	ADDA STRDCNT
	STAA VALUE

LOADVAL	LDAB LB	;Loads the lower bit of the counter into ACCA.


COMP	LDAA 1, X	; Loads Accumulator B with the value found where X points 
	DEX		; Else decrements pointer X
	CBA		; Compares B to A
	BNE COMP	; Else loops


***  TAKES CARE OF DECIMAL POINT


OUTB	INX
	LDAA 0, X
	LDAB PEMODE
	CMPB PAUSED
	BNE LBL1	

DP2	LDAB #%10000000	; Else, load B with binary 10000000
	SBA		; Subtract B from A to account for Decimal
	BMI OC3END	; If the decimal is already lit, return to main

	
LBL1	STAA PORTB	; Stores value in A into PORTB


OC3END	DEC     CNTR          ;DEC TIME BASE COUNTER
	RTI  
