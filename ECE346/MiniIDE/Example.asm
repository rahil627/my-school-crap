*
* example.asm
*
* This program will write the text "Hello World" to the SCI and pulses PP0 with 1Hz.
* This sample was written for a M68EVB912B32 evaluation board (MC68HC912B32).
*

	.nolist
#include hc12.inc
	.list

	org RAM_START
;	org EE_START			;uncomment this line for an EEPROM version

	bra	Main

Hello	dc.b "Hello world!", $0d, $0a, 0

Main:	clr	COPCTL			;disable watchdog
	lds	#USER_STACKTOP		;load stackpointer

	;initialize sci
	movw	#$0034,SC0BDH		;baudrate 9600
	movb	#$00,SC0CR1		;loop mode disabled, 8,1,n
	movb	#$0c,SC0CR2		;transmitter & receiver enabled, sci irq disabled
	ldaa	SC0SR1			;clean up

	;write string to sci
	ldx	#Hello
	bra	L2
L1:	brclr	SC0SR1,$80,L1		;wait for TDRE
	staa	SC0DRL			;write byte to data transmit register
L2:	ldaa	1,X+			;get next byte
	bne	L1

	;pulse PP0 with 1Hz
	movb	#%01111111,PWCLK	;CON01, clk A = PCLK/128, clk B = PCLK/128
	movb	#%00000001,PWPOL	;source is clk A
	clr	PWCTL
	clr	PWTST
	movw	#$f424,PWPER0		;period
	movw	#$7a12,PWDTY0		;duty
	movb	#%00000011,PWEN		;enable PWEN0/1

	bra	*
