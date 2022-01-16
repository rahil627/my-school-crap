PORTE	EQU	$100A	;display
REG	EQU	$1000	;portA
BAUD	EQU	$102B
SCCR1	EQU	$102C
SCCR2	EQU	$102D
SCSR	EQU	$2E
SCDR	EQU	$102F
BIT3	EQU	%00001000
BIT5	EQU	%00100000
BIT7	EQU	%10000000
DATA	EQU	$0000
MAIN	EQU	$0100
;MAIN    EQU	$B600  ;From EEPROM
SCI_VEC	EQU	$00C4
COMBASE EQU 	$B5F0
DATBASE EQU 	$B5F1
INIT_LEN EQU 	$04   ;actually len-1
DATA_LEN EQU 	$07   ;actually len-1
CONT_LEN EQU 	$00
D_COUNT  EQU 	4
TOC2     EQU 	$1018
TMSK1    EQU 	$1022
TFLG1    EQU 	$1023
TCNT     EQU 	$100E
OC2_VEC  EQU 	$00DC
IC3_VEC  EQU 	$00E2
T_BASE2  EQU 	2000  ; 1 ms
T_BASE3  EQU 	50000 ; 25 ms

TCTL2	 EQU  	$1021	

OC2F     EQU 	%01000000
OC2I     EQU 	%01000000
IC3	 EQU 	$01
CONT1    EQU 	$80    ; DD RAM Address Set to 000 0000
CONT2    EQU 	$C0    ; DD RAM Address Set to 100 0000

OUTA	EQU	$FFB8
ONE_S	EQU	250	;1s
ONE_MS	EQU	4	;4ms

	ORG Data
CHARCNT	RMB 1
update	RMB 1
offset	RMB 1

CNTR    RMB 1

INIT_DATA	FCB $38             ; function set
		FCB $38             ; function set
		FCB $06             ; Entry mode set
		FCB $01             ; Clear display
		FCB $0F             ; Display On/Off control

;word		FCC 'Hello PC#'
word		FCC '~'
input		FCC '                #'
char_r		FCC 'A'
char_t		FCB $41

letter		FCB $41	;A
guesses		FCB $06	;number of guesses

	ORG Main

*****   Jump Table initialization *****

	LDD #OC2_SVC    ;main interrupt  
        STD OC2_VEC+1 

	LDD #SCI_ISR	;Initialize SCI interrupt
	STD SCI_VEC+1

	LDD #IC3_ISR	;button
	STD IC3_VEC+1

	CLR offset
	CLR update

******* Initialization of SCI registers ******

	CLR SCCR1

	LDAA #$30	;vsets baud rate to 9600 (AxIDE default)
	;LDAA #$35	;baud=300
	STAA BAUD
	
	LDAA #$24	; enables SCI receive and enables interrupt
	STAA SCCR2
	
*OC2/IC3 INTERRUPT INITIALIZATION       
        LDAA    #OC2I  | IC3        ; enable OC2
        STAA    TMSK1  	      	
        STAA    TFLG1
	LDAA 	#$11
	STAA	TCTL2 
	CLI                     

*--- LCD initialization sequence 
	LDAB    #INIT_LEN 
        LDX     #INIT_DATA

M1      LDAA    0,X
        STAA    COMBASE

        JSR	delay4

	INX
        DECB
        BGE     M1
*--- END LCD initialization sequence

*begin test-----------------------------
	
	LDAA	#'~'
	STAA	SCDR
	JSR	SCITX

again	TST	update	;loop until char is received
	BEQ	again

	LDAA	char_r	;display char received
	JSR	refresh
	STAA	DATBASE
	JSR	delay4

	INC	char_t	;letter to transmit
	JSR	SCITX

	CLR	update
	BRA	again

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

;while (portA=$89) scroll through alphabet
;41-5A
;begin

;infra	LDAA	REG	;if infrared
;	CMPA	#$8A
;	BNE	infra

;reset	LDAB	#$41	;reset alphabet
;scroll	CMPB	#$5B	;if(letter=the char after Z) go to A
;	BEQ	reset
	;JSR	OUTA
;	JSR	refresh	;uses accA
;	STAB	DATBASE	;display
;	JSR	delay4	;uses accA
;	JSR	delay1	;uses accA
;	INCB
;	LDAA	REG	;if not infrared
;	CMPA	#$88
;	BNE	scroll

;	LDAA	REG	;if(button is pressed) finish
;	CMPA	#$89	
	;BRA	begin
;	BRA	again

;	SWI		;should not get here

refresh	LDAA    #$01
	STAA	COMBASE
	JSR	delay4
	
	LDAA    #CONT2
        STAA    COMBASE
        JSR	delay4
	RTS

*end test-------------------------------	
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
	

SCI_ISR
	LDX #REG
	BRSET SCSR,X, BIT5, SCIRCV ; checks to see if RDRF is set, meaning the hardware is ready to receive a character
	BRSET SCSR,X, BIT7, SCITX  ; checks to see if TDRE is set, meaning the hardware is ready to transmit a character
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
	;BHI 	More	;branch if higher
	LDAA	char_t
	STAA	SCDR
	BCLR	SCCR2-REG,X,BIT7+BIT3	;finished transmitting, set SCI mode to receive
	RTI

IC3_ISR			;button
	LDX #REG
	BSET SCCR2-REG,X,BIT7+BIT3	;set for tranmit
	;CLR CHARCNT
	
	LDAA TFLG1
	ORAA IC3
	STAA TFLG1
	RTI
**********************************************
OC2_SVC LDAA  #OC2F          ;SET UP TO 
	STAA  TFLG1 	     ;CLEAR OC2F 
        LDD   TOC2   		 ;SET UP FOR NEXT INTERRUPT
        ADDD  #T_BASE2        ;ADD TIMEBASE (1 MS)
        STD   TOC2    		;AND STORE 
*----------------------------------------------
	DEC   CNTR           ;DEC TIME BASE COUNTER
OC2RTI  RTI                  ;EXIT
**********************************************
delay4
	LDAA    #ONE_MS
        STAA    CNTR
LOOPC2  LDAA    CNTR                ; 4ms delay
        BNE     LOOPC2
	RTS
delay1
	LDAA    #ONE_S
        STAA    CNTR
LOOPC3  LDAA    CNTR                ; 1s delay
        BNE     LOOPC3
	RTS		
