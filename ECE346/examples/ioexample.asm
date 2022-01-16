DATA    EQU  $100      ;Data segment begins at (hex)100
MAIN    EQU  $140      ;Executable program begins at (hex)150

INCHAR  EQU  $FFCD     ;Utility Subroutine WAITS FOR INPUT
OUTA    EQU  $FFB8     ;Utility program for outputting ASCII

DELIM   EQU  ','       ;Delimiter ASCII symbol is ","
*                                
*
        ORG     DATA
*
TERM    FCB   '#'      ;Create ASCII code for "#"
*       
*
        ORG   MAIN

LOOP    JSR   INCHAR   ; Wait for keyboard input  to ACCA
        
        CMPA  TERM     ; Is it the termination character ?
        BEQ   EXIT     ; If it is, exit, else
        LDAA  #DELIM   ; Load ASCII character for ","
        JSR   OUTA     ; Output delimiter character to screen
        BRA   LOOP 
		    ; Branch back to repeat
EXIT    SWI            ; Exit  Program 