*LCD Test code
* Change History
* 4/01/03  Created by James M. Mengert
* 3/30/04  Modified by Dr. Lee Belfore
*          Arrays accessed from low to high addresses
*          Comments added, Orgs changed, 
*          index off of REGBAS

DATA    EQU $0000
*MAIN    EQU $B700  ;From EPROM
MAIN    EQU $0100  ;From RAM

* Equates for the LCD display
*    be sure to configure EVBU board for 
*    expanded mode
COMBASE EQU $B5F0
DATBASE EQU $B5F1
REGBAS   EQU $1000
TOC2     EQU $18
TMSK1    EQU $22
TFLG1    EQU $23
TCNT     EQU $0E

OC2F     EQU %01000000
OC2I     EQU %01000000
INIT_LEN EQU $04   ;actually len-1
DATA_LEN EQU $07   ;actually len-1
CONT_LEN EQU $00
T_BASE   EQU 2000  ; 1 ms
D_COUNT  EQU 4
OC2_VEC  EQU $DC
        
*
         ORG DATA
INIT_DATA   FCB $38             ; function set
            FCB $38             ; function set
            FCB $06             ; Entry mode set
            FCB $01             ; Clear display
            FCB $0F             ; Display On/Off control

CONT1       FCB $80    ; DD RAM Address Set to 000 0000

CONT2       FCB $C0    ; DD RAM Address Set to 100 0000

* Note the LCD data takes ASCII input directly
DATA1       FCB 'M'
            FCB 'i'
            FCB 'c'
            FCB 'r'
            FCB 'o'
            FCB 'c'
            FCB 'o'
            FCB 'n'

DATA2       FCB 't'
            FCB 'r'
            FCB 'o'
            FCB 'l'
            FCB 'l'
            FCB 'e'
            FCB 'r'
            FCB 's'

CNTR     RMB 1
*
        ORG MAIN
*JUMP TABLE INITIALIZATION
        LDAA    #$7E          ;SET UP OC2 JUMP TABLE
        STAA    OC2_VEC       ;FOR JMP
        LDD     #OC2_SVC      ;TO
        STD     OC2_VEC+1     ;OC2 INTERRUPT SERVICE
*TOC2 INITIALIZATION
        LDD     REGBAS+TCNT
        STD     REGBAS+TOC2
*OC2 INTERRUPT INITIALIZATION        
        LDAA    #OC2I         ;SET UP OC2 INTERRUPT
        STAA    REGBAS+TMSK1  ;ENABLE OC2 IN MASK REGISTER
        LDAA    #OC2F         ;SET UP TO CLEAR
        STAA    REGBAS+TFLG1  ;OC3  INTERRUPT FLAG
        CLI                   ;ENABLE SYSTEM INTERRUPT



* LCD initialization sequence
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
* Display in first 8 display positions
        LDAA    CONT1
        STAA    COMBASE
        LDAA    #D_COUNT
        STAA    CNTR
LOOPC1  LDAA    CNTR          ; 4ms delay
        BNE     LOOPC1
* Output first 8 characters       
        LDAB    #DATA_LEN
        LDX     #DATA1
M2      LDAA    0,X
        STAA    DATBASE
        LDAA    #D_COUNT
        STAA    CNTR
LOOP2   LDAA    CNTR                ; 4ms delay
        BNE     LOOP2
	INX
        DECB
        BGE     M2
* Display in second 8 display positions
        LDAA    CONT2
        STAA    COMBASE
        LDAA    #D_COUNT
        STAA    CNTR
LOOPC2  LDAA    CNTR                ; 4ms delay
        BNE     LOOPC2
* Output second 8 characters
        LDAB  #DATA_LEN
        LDX   #DATA2
M4      LDAA  0,X
        STAA  DATBASE
        LDAA  #D_COUNT
        STAA  CNTR
LOOP4   LDAA  CNTR                ; 4ms delay
        BNE   LOOP4
        INX
        DECB 
        BGE   M4
        SWI                ; return to Buffalo


*
*       OC2 INTERRUPT SERVICE ROUTINE
*       INTERRUPTS EVERY 1 MS AND DECREMENTS A COUNTER
*       PROVIDES A TIME BASE EVENT EVERY 1 MS
*        
*        
OC2_SVC LDAA  #OC2F          ;SET UP TO 
        STAA  REGBAS+TFLG1   ;CLEAR OC2F
        LDD   REGBAS+TOC2    ;SET UP FOR NEXT INTERRUPT
        ADDD  #T_BASE        ;ADD TIMEBASE (1 MS)
        STD   REGBAS+TOC2    ;AND STORE 
        DEC   CNTR           ;DEC TIME BASE COUNTER
        RTI                  ;EXIT


