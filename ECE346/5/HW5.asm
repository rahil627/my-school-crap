DATA    EQU $0000

MAIN    EQU $0100  ;From RAM

COMBASE EQU $B5F0
DATBASE EQU $B5F1

TOC2     EQU $1018
TOC3     EQU $101A	; Output compare register

TMSK1    EQU $1022
TFLG1    EQU $1023
TCNT     EQU $100E

OC2F     EQU %01000000
OC2I     EQU %01000000

OC3F     EQU %00100000
OC3I     EQU %00100000


INIT_LEN EQU $04   ;actually len-1
DATA_LEN EQU $0F   ;actually len-1
CONT_LEN EQU $00

T_BASE2   EQU 2000  ; 1 ms
T_BASE3   EQU 50000 ; 25 ms

D_COUNT  EQU 4

OC2_VEC  EQU $00DC
OC3_VEC  EQU $00D9

SERCOM	EQU $102F
SSTATUS	EQU $102E
PORTA	 EQU $1000
        

         ORG DATA

INIT_DATA   FCB $38             ; function set
            FCB $38             ; function set
            FCB $06             ; Entry mode set
            FCB $01             ; Clear display
            FCB $0F             ; Display On/Off control

CONT1       FCB $80    ; DD RAM Address Set to 000 0000

CONT2       FCB $C0    ; DD RAM Address Set to 100 0000

* Note the LCD data takes ASCII input directly
DATA1       FCC '**Hello World**#'
*DATA1	    FCC 'A#'
DATA2	    RMB 16

CHANGE	    RMB 1
CHANGE2	    RMB	1

CNTR        RMB 1

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
        LDAA    #OC2I              ;enable OC2
        STAA    TMSK1  	      
	LDAA	#OC2F		   ;turn on OC2	
        STAA    TFLG1 	      
        CLI         

*--- Serial initalization
	;LDAA	#$5
	;STAA	$102B
	CLR	$102C
	LDAA	#%00000100 ;Recieve Stuff
	STAA	$102D


*--- LCD initialization sequence
        LDAB    #INIT_LEN 
        LDX     #INIT_DATA

M1      LDAA    0,X
        STAA    COMBASE

        LDAA    #D_COUNT
        STAA    CNTR
LOOP1   LDAA    CNTR          ; 4ms delay
        BNE     LOOP1

	INX
        DECB
        BGE     M1
	BRA	BUTLOOP
*--- END LCD initialization sequence

*--- Display in first 8 display positions

TRANSMIT LDAA	#%00001000 	;Transmit Stuff
	STAA	$102D
	CLR	SSTATUS		;Clear to enable reading of next character
	LDAA    CONT1
        STAA    COMBASE		;Initalize the first line of the LCD

        LDAA    #D_COUNT
        STAA    CNTR
LOOPC1  LDAA    CNTR          	; 4ms delay
        BNE     LOOPC1

*-- Output first 16 characters       
        LDAB    #DATA_LEN
        LDX     #DATA1

M2      CLR	SSTATUS		;Clear to enable reading of next character
	LDAA    0,X
        STAA    DATBASE		;Send message character to LCD to confirm output
	STAA	SERCOM		;Send message to serial

        LDAA    #D_COUNT
        STAA    CNTR
LOOP2   LDAA    CNTR            ; 4ms delay
        BNE     LOOP2

	INX
        DECB
        BGE     M2
*-- End output of first 16 characters 
	
*	LDAA    #250
*        STAA    CNTR
*WAITSUM LDAA    CNTR                ; 250ms delay
*        BNE     WAITSUM
	
	LDAA	#%00000100 	;Recieve Stuff
	STAA	$102D

	LDAA	SSTATUS		;Set the current state of the status register for later compairson
	STAA	CHANGE

	BRA	WAIT

*--- END Display in first 8 display positions

*--- Begin serial communication
BUTLOOP	LDAA	PORTA		;Checks to see if the button has been pressed
	CMPA	#$89
	BEQ	TRANSMIT
	BRA	BUTLOOP
*--- End serial communication

WAIT	LDAA	SSTATUS		;Checks to see if the status register has changed
	CMPA	CHANGE
	BNE	RECIEVE		;If so then go to recieve mode
	BRA	WAIT		;If not then check again

WAIT2	LDAA	SSTATUS		;Checks to see if the status register has changed
	CMPA	CHANGE
	BNE	GETMONE		;If so then go to recieve mode
	BRA	WAIT2		;If not then check again

*--- Second 8 display positions

RECIEVE LDAA	#%00000100 ;Recieve Stuff
	STAA	$102D
	LDAA    CONT2
        STAA    COMBASE

        LDAA    #D_COUNT
        STAA    CNTR
LOOPC2  LDAA    CNTR                ; 4ms delay
        BNE     LOOPC2

* Output second 8 characters

        LDAB    #DATA_LEN	;Loads the maximum characters acceptable incase the computer program messes up


GETMONE	CLR	SSTATUS		;Enables the microcontroller to read each character sent, otherwise it only sees the first character
	LDAA    SERCOM		;Get the character from the serial communication area
	CMPA	#$23		;Check if a # was sent to the microcontroller
	BEQ	BUTLOOP		;If so then exit back to button push to transmit

	;CMPA	CHANGE		;Check to see if this is the same as the last character
	;BEQ	GETMONE		;Keeps the buffer from overwriting the entire screen
	STAA    DATBASE		;When word changes from the previous character it is sent to the LCD
	;STAA	CHANGE		;And the word to compair from the previous is changed

	LDAA    #D_COUNT		
        STAA    CNTR
LOOP3   LDAA    CNTR                ; 4ms delay
        BNE     LOOP3

	DECB			;Remove one from the max of 16 letters
	BEQ	BUTLOOP		;Exit if out of characters

	LDAA	SSTATUS		;Set the current state of the status register for later compairson
	STAA	CHANGE
	CMPA	#%00000000	
	BNE	WAIT2

	BRA	GETMONE




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



