DATA    EQU $0000	;start of data segment
MAIN    EQU $0100	;start of program segment

*LCD display
COMBASE EQU $B5F0
DATBASE EQU $B5F1


*interrupts
TMSK1    EQU $1022
TFLG1    EQU $1023
TCNT     EQU $100E	;free running counter

;output compare registers
TOC2     EQU $1018
TOC3     EQU $101A

;masks for manipulating output compare interrupts
OC2F     EQU %01000000
OC2I     EQU %01000000

OC3F     EQU %00100000
OC3I     EQU %00100000

;pseudo-vector location for OCs
OC2_VEC  EQU $00DC
OC3_VEC  EQU $00D9

*from HW4
INIT_LEN EQU $04
DATA_LEN EQU $0F	;16
CONT_LEN EQU $00	;?

TIME_BASE   EQU 2000	;1 ms

D_COUNT  EQU 4		;4ms

*SCI system registers
SCCR2	EQU $102D	;SCI Control Register 2
SCSR	EQU $102E	;SCI Status Register
SCDR	EQU $102F	;SCI Data Register
PORTA	EQU $1000
        

         ORG DATA ;initialize data
	    ;LCD values from hw4
INIT_DATA   FCB $38	;function set
            FCB $38	;function set
            FCB $06	;entry mode set
            FCB $01	;clear display
            FCB $0F	;display on/off control

CONT1       FCB $80    ; DD RAM Address Set to 000 0000
CONT2       FCB $C0    ; DD RAM Address Set to 100 0000

STRING FCB 'a','b','c','d','e','f','g','#'

* Note the LCD data takes ASCII input directly
FIRST	RMB 0
CNTR	RMB 1
CNTR2	RMB 1
SCCRch	RMB 1
CNTR3	RMB 1

        ORG MAIN
*JUMP TABLE INITIALIZATION
        LDAA    #$7E          
	STAA    OC2_VEC
        LDD     #OC2_SVC      
        STD     OC2_VEC+1     

*TOC2 INITIALIZATION
        LDD     TCNT
        STD     TOC2

*OC2 INTERRUPT INITIALIZATION        
        LDAA    #OC2I		;enable OC2 in mak register
        STAA    TMSK1  	      
	LDAA	#OC2F		;clear OC3(2?) interrupt flag 
        STAA    TFLG1
        CLI         		;enable system interrupt

*LCD initialization
        LDAB    #INIT_LEN	;b=4?
        LDX     #INIT_DATA	;x=16

M1	LDAA    0,X		;a=x
        STAA    COMBASE		;display value x is pointing to

        LDAA    #D_COUNT	;cntr=4
        STAA    CNTR
LOOP1   LDAA    CNTR		;4ms delay
        BNE     LOOP1
	INX			;next block
        DECB
        BGE     M1		;branch if greater than 0?
	;BRA	BUTTON

*serial initalization
	;LDAA	#$5		;don't have to change baud rate yet...
	;STAA	$102B		;BRCR/BAUD
	CLR	$102C		;SCCR1 (not used)
	CLR	SCDR		;temp
	CLR	SCSR		;temp
	LDAA	#%00000100	;receive enable (RE), not sure if needed
	STAA	SCDR

*main code starts here--------------------------------------------------------
BUTTON	LDAA	PORTA		;loop until button is pushed
	CMPA	#$89
	BNE	BUTTON

	LDAA	#%00001000 	;transmit enable (TE)
	STAA	SCCR2
	CLR	SCSR

	LDAB	#DATA_LEN	;16
	LDX	#STRING
	
*first transmit
ftrans	;LDAA	#'#'
	LDAA	0,X		;load char from string
	STAA	SCDR		;store on scdr
trans0	LDAA	SCSR		;loop until transmitted
	ANDA	#%11000000
	CMPA	#%11000000
	BEQ	next
	BRA	trans0

next	INX
	DECB
	BGE	ftrans
	BRA	go

*all other transmits
begin	LDY	#STRING

DISP3	LDAA    CONT2
        STAA    COMBASE
        LDAA    #D_COUNT
        STAA    CNTR

LOPC3   LDAA    CNTR          ; 4ms delay
        BNE     LOPC3
        LDAB    #DATA_LEN

M2      LDAA	CNTR3
	CMPA	#'0'
	BEQ	BUTTON
	LDAA    0,Y
        STAA    DATBASE
        LDAA    #D_COUNT
        STAA    CNTR

LOP4    LDAA    CNTR	;4ms delay
        BNE     LOP4
	INY
        DEC	CNTR3
	DECB	
	BGE     M2
	BRA	BUTTON
	BRA	go	
		
go	LDAA	#%00000100	;Receive Enable (RE)
	STAA	SCCR2
	LDY	#STRING

outLCD	LDAB	SCDR

receive	LDAA	SCSR		;loop until received
	ANDA	#%00100000
	CMPA	#%00100000
	BEQ	goLCD
	BRA	receive

goLCD	CMPB	#'#'
	BEQ	begin
	STAB	0,Y
	INC	CNTR3
	INY		
	BRA	outLCD

**********************************************
OC2_SVC LDAA  #OC2F		;SET UP TO 
	STAA  TFLG1 		;CLEAR OC2F 
        LDD   TOC2		;SET UP FOR NEXT INTERRUPT
        ADDD  #TIME_BASE	;ADD TIMEBASE (1 MS)
        STD   TOC2    		;AND STORE 
*----------------------------------------------
	DEC   CNTR           	;DEC TIME BASE COUNTER
OC2RTI  RTI                  	;EXIT

