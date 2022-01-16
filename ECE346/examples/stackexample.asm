*Equates
DATA   EQU  $0000   ;Data segment begins at (hex)100
MAIN   EQU  $0100   ;Executable program begins at (hex)140

       ORG   DATA

A1     FCB   $41,$74,$20,$41,$31,1,2,3,4,5  ;Initial A1
A2     FCB   $41,$74,$20,$41,$32,6,7,8,9,10 ;Initial A2

       ORG     MAIN

INIT   LDAA    #10      ;Initialize  for 10 iterations
       LDX     #A1      ;Set up index address pointer to A1
       LDY     #A2      ;Set up index address pointer to A2

LOOP   PSHA             ;Store loop counter on stack
       LDAA    0,X      ;First get byte from A1
       LDAB    0,Y      ;Next get byte from A2
       STAA    0,Y      ;Store byte from A1 in A2
       STAB    0,X      ;Store byte from A2 in A1
       INX              ;increment A1 pointer
       INY              ;increment B2 pointer
       PULA             ;retrieve loop counter from stack
       DECA             ;decrement loop counter
       BNE     LOOP     ;branch back if counter not zero

EXIT   SWI              ;else exit           

