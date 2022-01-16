*equates---------------------------------------------------------------------
DATA    EQU	$0000	;start of data segment
MAIN    EQU	$0100	;start of program segment
;MAIN    EQU	$B600  ;From EEPROM
BAUD	EQU	$102B

*interrupts
TOC2	EQU 	$1018
TMSK1	EQU 	$1022
TFLG1	EQU 	$1023
TCNT	EQU 	$100E
OC2_VEC	EQU 	$00DC
IC3_VEC	EQU 	$00E2
T_BASE2	EQU 	2000  ; 1 ms
T_BASE3	EQU 	50000 ; 25 ms

TCTL2	EQU  	$1021	

;masks for manipulating output compare interrupts
OC2F	EQU 	%01000000
OC2I	EQU 	%01000000
IC3	EQU 	$01

*LCD display
PORTE	EQU	$100A	;display
COMBASE	EQU 	$B5F0	;line?
CONT1	EQU 	$80	;line 1
CONT2	EQU 	$C0	;line 2
DATBASE	EQU 	$B5F1	;store on this
INIT_LEN EQU 	$04
DATA_LEN EQU 	$07
CONT_LEN EQU 	$00
;D_COUNT	EQU 	4

*SCI system registers
REG	EQU	$1000	;portA - button/infrared
SCCR1	EQU	$102C	;SCI Control Register 1
SCCR2	EQU	$102D	;SCI Control Register 2
SCSR	EQU	$2E	;SCI Status Register
SCDR	EQU	$102F	;SCI Data Register

BIT3	EQU	%00001000
BIT5	EQU	%00100000	;receive data register full (RDRF)
BIT7	EQU	%10000000	;transmit data register empty (TDRE)

SCI_VEC	EQU	$00C4

*new
OUTA	EQU	$FFB8	;OUTA outputs accA ASCII character to terminal
ONE_S	EQU	250	;1s
ONE_MS	EQU	4	;4ms
TILDE	EQU	$7E
*data-------------------------------------------------------------------------
	ORG Data
CHARCNT	RMB 1
update	RMB 1
;offset	RMB 1
CNTR    RMB 1 ;used for intterupt

INIT_DATA FCB $38 ;function set
	FCB $38	;function set
	FCB $06	;Entry mode set
	FCB $01	;Clear display
	FCB $0F	;Display On/Off control

;word	FCC 'Hello PC#'
input	FCC '                #'
char_r	FCC 'A'
char_t	FCB $7E;$41

letter	FCB $41	;A
guesses	FCB $06	;number of guesses
*main initializations-----------------------------------------------------------
	ORG Main

*JUMP TABLE INITIALIZATION
	LDD #OC2_SVC    ;main interrupt  
        STD OC2_VEC+1 

	LDD #SCI_ISR	;SCI interrupts
	STD SCI_VEC+1

	LDD #IC3_ISR	;button
	STD IC3_VEC+1

	;CLR offset
	CLR update
*Initialization of SCI registers
	CLR SCCR1

	LDAA #$30	;sets baud rate to 9600 (AxIDE default)
	;LDAA #$35	;baud=300
	STAA BAUD
	
	LDAA #$24	;00100100 - receive enable (RE) and receive interrupt enable (RIE), start with receive
	;LDAA #$28	;00101000 - transmit enable (TE) and RIE
	STAA SCCR2
	
*OC2/IC3 INTERRUPT INITIALIZATION       
        LDAA    #OC2I  | IC3	;enable OC2
        STAA    TMSK1  	      	
        STAA    TFLG1
	LDAA 	#$11
	STAA	TCTL2 
	CLI     		;clear interrupt mask                

*LCD initialization sequence 
	LDAB    #INIT_LEN 	;4 hexes in init data
        LDX     #INIT_DATA

M1      LDAA    0,X
        STAA    COMBASE
        JSR	delay4
	INX
        DECB
        BGE     M1		;compare x to b

*main code starts here--------------------------------------------------------
	;LDAA	#'T'	OUTA not working :(
	;JSR	OUTA
	;SWI

	;LDAA	#TILDE
	;LDAA	#'~'
	;STAA	SCDR
	;JSR	SCITX

begin	TST	update	;loop until button is pushed
	BEQ	begin

	JSR	refresh	;display char received
	LDAA	char_r
	STAA	DATBASE
	JSR	delay4

	;INC	char_t	;letter to transmit

	CLR	update
	BRA	begin

;while (portA=$89) scroll through alphabet
;41-5A
;begin

	SWI		;should not get here
*subroutines-----------------------------------------------------------------
refresh	LDAA    #$01	;refresh LCD
	STAA	COMBASE
	JSR	delay4
	
	LDAA    #CONT2
        STAA    COMBASE
        JSR	delay4
	RTS

delay4	LDAA    #ONE_MS	;4ms delay
        STAA    CNTR
LOOPC2  LDAA    CNTR
        BNE     LOOPC2
	RTS

delay1	LDAA    #ONE_S	;1s delay
        STAA    CNTR
LOOPC3  LDAA    CNTR
        BNE     LOOPC3
	RTS

infra	LDAA	REG	;if infrared
	CMPA	#$8A
	BNE	infra

reset	LDAB	#$40	;reset alphabet
scroll	CMPB	#$5B	;if(letter==the char after Z) go to A
	BEQ	reset
	;JSR	OUTA
	JSR	refresh	;uses accA
	STAB	DATBASE	;display
	JSR	delay4	;uses accA
	JSR	delay1	;uses accA
	INCB

	LDAA	REG	;if not infrared
	CMPA	#$88
	BNE	scroll

	LDAA	REG	;if(button is pressed) finish
	CMPA	#$89

	STAB	char_t

	CMPB	#$40	;if(letter==@) letter=~
	BNE	return
	LDAA	#$7E
	STAA	char_t

return	RTS
*hw5 solution-----------------------------------------------------------------
;again	TST	update
;	BEQ	again

;	JSR	refresh
        
;	LDX   #input	;init input
;	LDAA  0,X
;M4      STAA  DATBASE	;store input
;	JSR   delay4        
;        INX
;	LDAA  0,X
;	CMPA  #'#'
;	BNE   M4

;	CLR	update

;	LDX 	#input
;erase	LDAA	#' '
;	STAA	0,X
;	INX
;	CPX	#input+15
;	BNE	erase
	
;	BRA	again
*interrupts------------------------------------------------------------------
SCI_ISR				;interrupts upon receive or button?
	;if receive data register is full, go to SCIRCV
	;if transmit data register is empty, go to SCITX
	LDX #REG
	;branch if all selected bits are set: BRSET opcode,operand_address,relative_offset
	BRSET SCSR,X, BIT5, SCIRCV ;checks to see if RDRF is set, meaning the hardware is ready to receive a character
	BRSET SCSR,X, BIT7, SCITX  ;checks to see if TDRE is set, meaning the hardware is ready to transmit a character
	RTI

SCIRCV			;receive from PC and store onto input
	;LDX  #input
	LDAA 	SCDR
	STAA 	char_r
	;CMPA #'#'
	;BEQ  fin
	;LDAB offset
	;ABX
	;STAA 0,X
	;INC  offset
	;BRA  return	;RTI
	INC	update
	RTI

SCITX			;tranmit x
	;LDAA	#9	;max char
	;CMPA 	CHARCNT	;see if message is complete
	;BHI 	more	;branch if higher
	JSR	infra
	LDAA	char_t
	STAA	SCDR
	;JSR	delay4	;temp
	BCLR	SCCR2-REG,X,BIT7+BIT3	;finished transmitting, set SCI mode to receive
	RTI

;more	LDX #word	;continue to next character
;	LDAB CHARCNT
;	ABX
;	LDAA 0,X	;add b to x
;	STAA SCDR	;load character into SCDR to be transmitted
;	INC CHARCNT

IC3_ISR			;button
	LDX #REG
	BSET SCCR2-REG,X,BIT7+BIT3	;set for tranmit
	;CLR CHARCNT
	
	LDAA TFLG1
	ORAA IC3
	STAA TFLG1
	RTI
*interrupts------------------------------------------------------------------
OC2_SVC LDAA  #OC2F	;SET UP TO 
	STAA  TFLG1	;CLEAR OC2F 
        LDD   TOC2	;SET UP FOR NEXT INTERRUPT
        ADDD  #T_BASE2	;ADD TIMEBASE (1 MS)
        STD   TOC2	;AND STORE 
*----------------------------------------------
	DEC   CNTR	;DEC TIME BASE COUNTER
OC2RTI  RTI		;EXIT

