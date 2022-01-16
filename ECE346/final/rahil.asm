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
BIT2	EQU	%00000100

SCI_VEC	EQU	$00C4

*new
OUTA	EQU	$FFB8	;not working :(
ONE_S	EQU	250	;1s
FOUR_MS	EQU	4	;4ms
TILDE	EQU	$7E
*data-------------------------------------------------------------------------
	ORG Data
update	RMB 1
offset	RMB 1
CNTR    RMB 1 ;used for intterupt

INIT_DATA FCB $38 ;function set
	FCB $38	;function set
	FCB $06	;Entry mode set
	FCB $01	;Clear display
	FCB $0F	;Display On/Off control

input	FCC '                #'
word	FCC '                #'
char_r	FCC 'A'
char_t	FCC '~'	;$41

;guesses	FCB $06	;number of guesses
length	FCC 'D'	;length of word
length2	FCC 'A' ;length of input
pos	FCC 'U' ;position of letter

char_d	FCB $5A	;Z for debugging
count	FCB $00	;counter
count2	FCB $00
count3	FCB $00
*main initializations-----------------------------------------------------------
	ORG Main

*JUMP TABLE INITIALIZATION
	LDD #OC2_SVC    ;main interrupt  
        STD OC2_VEC+1 

	LDD #SCI_ISR	;SCI interrupts
	STD SCI_VEC+1

	LDD #IC3_ISR	;button
	STD IC3_VEC+1

	;clear for EEPROM?
	CLR offset
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
	;LDAA	#TILDE
	;LDAA	#'~'
	;STAA	SCDR
	;JSR	SCITX
begin	TST	update	;loop until button is pushed
	BEQ	begin

	JSR	line2	;debug (display decimal)
	LDAA	pos
	ADDA	#$30
	STAA	DATBASE
	JSR	delay4

;	JSR	line2	;debug (display input)
;	LDX	#input
;	LDAA	0,X
;loop4   STAA	DATBASE
;	JSR	delay4
;	INX
;	LDAA	0,X
;	CMPA	#'#'
;	BNE	loop4

	JSR	line1	;display word
	LDX	#word
	LDAA	0,X
loop3   STAA	DATBASE
	JSR	delay4
	INX
	LDAA	0,X
	CMPA	#'#'
	BNE	loop3

	JSR	infra	;input letter

	CLR	update
	BRA	begin

	SWI		;should not get here
*subroutines-----------------------------------------------------------------
refresh	LDAA    #$01	;refresh LCD
	STAA	COMBASE
	JSR	delay4
	
line2	LDAA    #CONT2	;select line
        STAA    COMBASE
        JSR	delay4
	RTS

line1	LDAA    #CONT1	;select line
        STAA    COMBASE
        JSR	delay4
	RTS

delay4	LDAA    #FOUR_MS ;4ms delay
        STAA    CNTR
LOOPC2  LDAA    CNTR
        BNE     LOOPC2
	RTS

delay1	LDAA    #ONE_S	;1s delay
        STAA    CNTR
LOOPC3  LDAA    CNTR
        BNE     LOOPC3
	RTS

;infra	LDAA	REG
;	CMPA	#$8A	;if infrared, scroll
;	BEQ	reset
;	CMPA	#$89	;if button, select
;	BEQ	select
;	BRA	infra

;reset	LDAB	#$41	;reset alphabet
;scroll	CMPB	#$5B	;if(letter==the char after Z) go to A
;	BEQ	reset
;	JSR	line2	;uses accA
;	STAB	DATBASE	;display
;	JSR	delay1	;uses accA
;	JSR	delay1

;	LDAA	REG	;if not infrared, stop & wait
;	CMPA	#$88
;	BEQ	infra

;	INCB		;else loop
;	BNE	scroll

infra	LDAA	REG	;if infrared
	CMPA	#$8A
	BNE	infra


;while (portA=$89) scroll through alphabet [41-5A]

reset	LDAB	#$41	;reset alphabet
scroll	CMPB	#$5B	;if(letter==the char after Z) go to A
	BEQ	reset
	JSR	line2	;uses accA
	STAB	DATBASE	;display
	JSR	delay4	;uses accA
	JSR	delay1	;uses accA

	LDAA	REG	;if not infrared
	CMPA	#$88
	BEQ	select

	INCB		;else loop
	BNE	scroll

select	STAB	char_t

return	RTS

debug	JSR	refresh
	LDAA	char_d
	STAA	DATBASE
	DECA
	STAA	char_d
	JSR	delay4
	RTS

;erase1	LDX 	#input
;erase	LDAA	#' '
;	STAA	0,X
;	INX
;	CPX	#input+15
;	BNE	erase
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
SCI_ISR	;checks this constantly
	;if receive data register is full, go to SCIRCV
	;if transmit data register is empty, go to SCITX
	LDX #REG
	;branch if all selected bits are set - BRSET opcode,operand_address,relative_offset
	BRSET SCSR,X, BIT5, SCIRCV ;checks to see if RDRF is set, meaning the hardware is ready to receive a character
	BRSET SCSR,X, BIT7, SCITX  ;checks to see if TDRE is set, meaning the hardware is ready to transmit a character
	RTI

SCITX	LDAA	char_t
	STAA	SCDR
	BCLR	SCCR2-REG,X,BIT7+BIT3	;finished transmitting, set SCI mode to receive

	BSET SCCR2-REG,X,BIT5+BIT2	; Set to Receive
	RTI

SCIRCV	BCLR SCCR2-REG,X,BIT5+BIT2	; Clear receive


	LDX	#word	;input=receive
	LDAB	SCDR
			
	LDAA	#$00	;first time, store underscores
	CMPA	count
	BEQ	s_len


	;BLO	fin	;skip first 2 times
	;BEQ	s_len	;third time: store length
	;else continue	;after that, play game


	CMPB	#0	;if 0, get out
	BEQ	fin


	LDAA	#$00	;if first number, store number of characters found in word
	CMPA	count3
	BEQ	s_len2

	;LDAA	#$00
	;STAA	bool9
	LDAA	#$00
	STAA	count3

	STAB	pos

	;LDX	#input
	;LDAB	offset
	;ABX
	;STAA	0,X
	;INC	offset

	ABX
	DEX

	LDAA	char_t	;if not 0, add letter(s) to input
	STAA	0,X
	


fin	INC update
	;CLR offset
	RTI

s_len	;LDAA	SCDR	;b=SCDR, x=word
	STAB	length	;store length
	INC	count

	DECB		;store underscores
loopy	STAB	count2
	LDAA	#'_'
	STAA	0,X
	INX
	DECB
	TST	count2
	BNE	loopy

	BEQ	fin

s_len2	INC	count3
	STAB	length2
	BEQ	fin
	
	

IC3_ISR			;button
	LDX #REG
	BSET SCCR2-REG,X,BIT7+BIT3	;set for tranmit
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
