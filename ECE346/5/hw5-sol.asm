PORTE	EQU	$100A
REG	EQU	$1000
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

	ORG Data
CHARCNT	RMB 1
update	RMB 1
offset	RMB 1


CNTR    RMB 1


INIT_DATA   FCB $38             ; function set
            FCB $38             ; function set
            FCB $06             ; Entry mode set
            FCB $01             ; Clear display
            FCB $0F             ; Display On/Off control

Word	    FCC 'Hello PC#'
input	    FCC '                #'

	
	ORG Main

*****   Jump Table initialization *****

	LDD #OC2_SVC      
        STD OC2_VEC+1 

	LDD #SCI_ISR	; Initialize SCI interrupt
	STD SCI_VEC+1

	LDD #IC3_ISR
	STD IC3_VEC+1

	
	CLR offset
	CLR update

******* Initialization of SCI registers ******

	CLR SCCR1

	LDAA #$30	; sets baud rate to 9600 (AxIDE default)
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
	
again	TST	update
	BEQ	again

refresh	LDAA    #$01
	STAA	COMBASE
	JSR	delay4
	
	LDAA    #CONT2
        STAA    COMBASE
        JSR	delay4

        
	LDX   #input
	LDAA  0,X
M4      STAA  DATBASE
	JSR   delay4        
        INX
	LDAA  0,X
	CMPA  #'#'
	BNE   M4

	CLR	update

	LDX 	#input
erase	LDAA	#' '
	STAA	0,X
	INX
	CPX	#input+15
	BNE	erase
	

	BRA	again
	

SCI_ISR

	LDX #REG
	BRSET SCSR,X, BIT5, SCIRCV ; checks to see if RDRF is set, meaning the hardware is ready to receive a character
	
	BRSET SCSR,X, BIT7, SCITX  ; checks to see if TDRE is set, meaning the hardware is ready to transmit a character

	BRA return

SCIRCV	
	LDX  #input
	LDAA SCDR
	CMPA #'#'
	BEQ  fin
	LDAB offset
	ABX
	STAA 0,X
	INC  offset
	BRA  return
	
fin	INC update
	CLR offset	
	BRA return

SCITX		
	LDAA	#9
	CMPA 	CHARCNT			; see if message is complete
	BHI 	More
	
	BCLR SCCR2-REG,X,BIT7+BIT3	; finished transmitting, set SCI mode to receive
	BRA     return
	
more	LDX #word	;continue to next character
	LDAB CHARCNT
	ABX
	LDAA 0,X
	STAA SCDR	;load character into SCDR to be transmitted
	INC CHARCNT
	
return	RTI


IC3_ISR
	LDX #REG
	BSET SCCR2-REG,X,BIT7+BIT3
	CLR CHARCNT
	
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
	LDAA    #D_COUNT
        STAA    CNTR
LOOPC2  LDAA    CNTR                ; 4ms delay
        BNE     LOOPC2
	RTS	
