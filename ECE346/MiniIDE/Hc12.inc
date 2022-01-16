;
; hc12.inc : Common definitions for Motorola's M68EVB912B32 EVB (and more)
;
; Source: MC68HC812A4, MC68HC912B32 Technical Summary, 1997
; Copyright (c) 1998 MGTEK
;

;***** Memory Mapping *****

RAM_START	equ $0800
RAM_SIZE	equ $0400
USER_RAM_SIZE	equ $0200

EE_START	equ $0D00

STACKTOP	equ RAM_START+RAM_SIZE
USER_STACKTOP	equ RAM_START+USER_RAM_SIZE

#ifndef MAP_PAGE
MAP_PAGE	equ $00
#endif

;***** Register *****

PORTA		equ $00+MAP_PAGE	;Port A Data Register
PORTB		equ $01+MAP_PAGE	;Port B Data Register
DDRA		equ $02+MAP_PAGE	;Port A Data Direction Register
DDRB		equ $03+MAP_PAGE	;Port B Data Direction Register
PORTC		equ $04+MAP_PAGE	;Port C Data Register
PORTD		equ $05+MAP_PAGE	;Port D Data Register
DDRC		equ $06+MAP_PAGE	;Port C Data Direction Register
DDRD		equ $07+MAP_PAGE	;Port D Data Direction Register
PORTE		equ $08+MAP_PAGE	;Port E Data Register
DDRE		equ $09+MAP_PAGE	;Port E Data Direction Register
PEAR		equ $0A+MAP_PAGE	;Port E Assigment Register
MODE		equ $0B+MAP_PAGE	;Mode Register
PUCR		equ $0C+MAP_PAGE	;Pull Up Control Register
RDRIV		equ $0D+MAP_PAGE	;Reduced Drive of I/O Lines Register
INITRM		equ $10+MAP_PAGE	;Initialization of Internal RAM Position Register
INITRG		equ $11+MAP_PAGE	;Initialization of Internal Register Position Register
INITEE		equ $12+MAP_PAGE	;Initialization of Internal EEPROM Position Register
MISC		equ $13+MAP_PAGE	;Miscellaneous Mapping Control Register
RTICTL		equ $14+MAP_PAGE	;Real Time Interrupt Control Register
RTIFLG		equ $15+MAP_PAGE	;Real Time Interrupt Flag Register
COPCTL		equ $16+MAP_PAGE	;COP Control Register
COPRST		equ $17+MAP_PAGE	;Arm/Reset COP Timer register
ITST0		equ $18+MAP_PAGE	;Reserved
ITST1		equ $19+MAP_PAGE	;Reserved
ITST2		equ $1A+MAP_PAGE	;Reserved
ITST3		equ $1B+MAP_PAGE	;Reserved
INTCR		equ $1E+MAP_PAGE	;Interrupt Control Register
HPRIO		equ $1F+MAP_PAGE	;Highest Priority Interrupt Register
KWIED		equ $20+MAP_PAGE	;Key Wakeup Port D Interrupt Enable Register
BRKCT0		equ $20+MAP_PAGE	;Breakpoint Control Register 0
KWIFD		equ $21+MAP_PAGE	;Key Wakeup Port D Flag Register
BRKCT1		equ $21+MAP_PAGE	;Breakpoint Control Register 1
BRKAH		equ $22+MAP_PAGE	;Breakpoint Address Register High
BRKAL		equ $23+MAP_PAGE	;Breakpoint Address Register Low
PORTH		equ $24+MAP_PAGE	;Port H Data Register
BRKDH		equ $24+MAP_PAGE	;Breakpoint Data Register High
DDRH		equ $25+MAP_PAGE	;Port H Data Direction Register 
BRKDL		equ $25+MAP_PAGE	;Breakpoint Data Register Low
KWIEH		equ $26+MAP_PAGE	;Key Wakeup Port H Interrupt Enable Register
KWIFH		equ $27+MAP_PAGE	;Key Wakeup Port H Flag Register
PORTJ		equ $28+MAP_PAGE	;Port J Data Register
DDRJ		equ $29+MAP_PAGE	;Port J Data Direction Register
KWIEJ		equ $2A+MAP_PAGE	;Key Wakeup Port J Interrupt Enable Register
KWIFJ		equ $2B+MAP_PAGE	;Key Wakeup Port J Flag Register
KPOLJ		equ $2C+MAP_PAGE	;Key Wakeup Port J Polarity Register
PUPSJ		equ $2D+MAP_PAGE	;Key Wakeup Port J Pull-up / Pull-down Select Register
PULEJ		equ $2E+MAP_PAGE	;Key Wakeup Port J Pull-up / Pull-down Enable Register
PORTF		equ $30+MAP_PAGE	;Port F Data Register
PORTG		equ $31+MAP_PAGE	;Port G Data Register
DDRF		equ $32+MAP_PAGE	;Port F Data Direction Register 
DDRG		equ $33+MAP_PAGE	;Port G Data Direction Register 
DPAGE		equ $34+MAP_PAGE	;Data Page Register
PPAGE		equ $35+MAP_PAGE	;Program Page Register
EPAGE		equ $36+MAP_PAGE	;Extra Page Register
WINDEF		equ $37+MAP_PAGE	;Window Definition Register
MXAR		equ $38+MAP_PAGE	;Memory Expansion Assignment Register
CSCTL0		equ $3C+MAP_PAGE	;Chip Select Control Register 0
CSCTL1		equ $3D+MAP_PAGE	;Chip Select Control Register 1
CSSTR0		equ $3E+MAP_PAGE	;Chip Select Stretch Register 0
CSSTR1		equ $3F+MAP_PAGE	;Chip Select Stretch Register 1
LDVH		equ $40+MAP_PAGE	;Loop Divider Register High
PWCLK		equ $40+MAP_PAGE	;PWM Clocks and Concatenate
LDVL		equ $41+MAP_PAGE	;Loop Divider Register Low
PWPOL		equ $41+MAP_PAGE	;PWM Clock Select and Polarity
RDVH		equ $42+MAP_PAGE	;Reference Divider Register High
PWEN		equ $42+MAP_PAGE	;PWM Enable
RVDL		equ $43+MAP_PAGE	;Reference Divider Register Low
PWPRES		equ $43+MAP_PAGE	;PWM Prescale Counter
PWSCAL0		equ $44+MAP_PAGE	;Scale Register 0
PWSCNT0		equ $45+MAP_PAGE	;PWM Scale Counter 0
PWSCAL1		equ $46+MAP_PAGE	;Scale Register 1
CLKCTL		equ $47+MAP_PAGE	;Clock Control Register
PWSCNT1		equ $47+MAP_PAGE	;PWM Scale Counter 1
PWCNT0		equ $48+MAP_PAGE	;PWM Channel 0 Counter
PWCNT1		equ $49+MAP_PAGE	;PWM Channel 1 Counter
PWCNT2		equ $4A+MAP_PAGE	;PWM Channel 2 Counter
PWCNT3		equ $4B+MAP_PAGE	;PWM Channel 3 Counter
PWPER0		equ $4C+MAP_PAGE	;PWM Channel 0 Period Register
PWPER1		equ $4D+MAP_PAGE	;PWM Channel 1 Period Register
PWPER2		equ $4E+MAP_PAGE	;PWM Channel 2 Period Register
PWPER3		equ $4F+MAP_PAGE	;PWM Channel 3 Period Register
PWDTY0		equ $50+MAP_PAGE	;PWM Channel 0 Duty Register
PWDTY1		equ $51+MAP_PAGE	;PWM Channel 1 Duty Register
PWDTY2		equ $52+MAP_PAGE	;PWM Channel 2 Duty Register
PWDTY3		equ $53+MAP_PAGE	;PWM Channel 3 Duty Register
PWCTL		equ $54+MAP_PAGE	;PWM Control Register
PWTST		equ $55+MAP_PAGE	;PWM Special Mode Register
PORTP		equ $56+MAP_PAGE	;Port P Data Register
DDRP		equ $57+MAP_PAGE	;Port P Data Direction Register
ATDCTL0		equ $60+MAP_PAGE	;Reserved
ATDCTL1		equ $61+MAP_PAGE	;Reserved
ATDCTL2		equ $62+MAP_PAGE	;ATD Control Register 2
ATDCTL3		equ $63+MAP_PAGE	;ATD Control Register 3
ATDCTL4		equ $64+MAP_PAGE	;ATD Control Register 4
ATDCTL5		equ $65+MAP_PAGE	;ATD Control Register 5
ATDSTATH	equ $66+MAP_PAGE	;ATD Status Register High
ATDSTATL	equ $67+MAP_PAGE	;ATD Status Register Low
ATDTSTH		equ $68+MAP_PAGE	;ATD Test Register High
ATDTSTL		equ $69+MAP_PAGE	;ATD Test Register Low
PORTAD		equ $6F+MAP_PAGE	;Port AD Data Input Register
ADR0H		equ $70+MAP_PAGE	;A/D Converter Result Register 0
ADR1H		equ $72+MAP_PAGE	;A/D Converter Result Register 1
ADR2H		equ $74+MAP_PAGE	;A/D Converter Result Register 2
ADR3H		equ $76+MAP_PAGE	;A/D Converter Result Register 3
ADR4H		equ $78+MAP_PAGE	;A/D Converter Result Register 4
ADR5H		equ $7A+MAP_PAGE	;A/D Converter Result Register 5
ADR6H		equ $7C+MAP_PAGE	;A/D Converter Result Register 6
ADR7H		equ $7E+MAP_PAGE	;A/D Converter Result Register 7
TIOS		equ $80+MAP_PAGE	;Timer Input Capture / Output Compare Select
CFORC		equ $81+MAP_PAGE	;Timer Compare Force Register
OC7M		equ $82+MAP_PAGE	;Output Compare 7 Mask Register
OC7D		equ $83+MAP_PAGE	;Output Compare 7 Data Register
TCNTH		equ $84+MAP_PAGE	;Timer Counter Register Hi $84
TCNTL		equ $85+MAP_PAGE	;Timer Counter Register Lo $85
TSCR		equ $86+MAP_PAGE	;Timer System Control Register $86
TQCR		equ $87+MAP_PAGE	;Reserved
TCTL1		equ $88+MAP_PAGE	;Timer Control Register 1
TCTL2		equ $89+MAP_PAGE	;Timer Control Register 2
TCTL3		equ $8A+MAP_PAGE	;Timer Control Register 3
TCTL4		equ $8B+MAP_PAGE	;Timer Control Register 4
TMSK1		equ $8C+MAP_PAGE	;Timer Interrupt Mask Register 1
TMSK2		equ $8D+MAP_PAGE	;Timer Interrupt Mask Register 2
TFLG1		equ $8E+MAP_PAGE	;Timer Interrupt Flag Register 1
TFLG2		equ $8F+MAP_PAGE	;Timer Interrupt Flag Register 2
TC0H		equ $90+MAP_PAGE	;Timer Input Capture / Output Compare Register 0 Hi $90
TC0L		equ $91+MAP_PAGE	;Timer Input Capture / Output Compare Register 0 Lo $91
TC1H		equ $92+MAP_PAGE	;Timer Input Capture / Output Compare Register 1 Hi $92
TC1L		equ $93+MAP_PAGE	;Timer Input Capture / Output Compare Register 1 Lo $93
TC2H		equ $94+MAP_PAGE	;Timer Input Capture / Output Compare Register 2 Hi $94
TC2L		equ $95+MAP_PAGE	;Timer Input Capture / Output Compare Register 2 Lo $95
TC3H		equ $96+MAP_PAGE	;Timer Input Capture / Output Compare Register 3 Hi $96
TC3L		equ $97+MAP_PAGE	;Timer Input Capture / Output Compare Register 3 Lo $97
TC4H		equ $98+MAP_PAGE	;Timer Input Capture / Output Compare Register 4 Hi $98
TC4L		equ $99+MAP_PAGE	;Timer Input Capture / Output Compare Register 4 Lo $99
TC5H		equ $9A+MAP_PAGE	;Timer Input Capture / Output Compare Register 5 Hi $9A
TC5L		equ $9B+MAP_PAGE	;Timer Input Capture / Output Compare Register 5 Lo $9B
TC6H		equ $9C+MAP_PAGE	;Timer Input Capture / Output Compare Register 6 Hi $9C
TC6L		equ $9D+MAP_PAGE	;Timer Input Capture / Output Compare Register 6 Lo $9D
TC7H		equ $9E+MAP_PAGE	;Timer Input Capture / Output Compare Register 7 Hi $9E
TC7L		equ $9F+MAP_PAGE	;Timer Input Capture / Output Compare Register 7 Lo $9F
PACTL		equ $A0+MAP_PAGE	;Pulse Accumulator Control Register
PAFLG		equ $A1+MAP_PAGE	;Pulse Accumulator Flag Register
PACNTH		equ $A2+MAP_PAGE	;Pulse Accumulator Count Register High
PACNTL		equ $A3+MAP_PAGE	;Pulse Accumulator Counter Register Low
TIMTST		equ $AD+MAP_PAGE	;Timer Test Register
PORTT		equ $AE+MAP_PAGE	;Timer Port T Data Register 
DDRT		equ $AF+MAP_PAGE	;Timer Port T Data Direction Register 
SC0BDH		equ $C0+MAP_PAGE	;SCI 0 Baud Rate Register High
SC0BDL		equ $C1+MAP_PAGE	;SCI 0 Baud Rate Register Low
SC0CR1		equ $C2+MAP_PAGE	;SCI 0 Control Register 1
SC0CR2		equ $C3+MAP_PAGE	;SCI 0 Control Register 2
SC0SR1		equ $C4+MAP_PAGE	;SCI 0 Status Register 1
SC0SR2		equ $C5+MAP_PAGE	;SCI 0 Status Register 2
SC0DRH		equ $C6+MAP_PAGE	;SCI 0 Data Register High
SC0DRL		equ $C7+MAP_PAGE	;SCI 0 Data Register Low
SC1BDH		equ $C8+MAP_PAGE	;SCI 1 Baud Rate Register High
SC1BDL		equ $C9+MAP_PAGE	;SCI 1 Baud Rate Register Low
SC1CR1		equ $CA+MAP_PAGE	;SCI 1 Control Register 1
SC1CR2		equ $CB+MAP_PAGE	;SCI 1 Control Register 2
SC1SR1		equ $CC+MAP_PAGE	;SCI 1 Status Register 1
SC1SR2		equ $CD+MAP_PAGE	;SCI 1 Status Register 2
SC1DRH		equ $CE+MAP_PAGE	;SCI 1 Data Register High
SC1DRL		equ $CF+MAP_PAGE	;SCI 1 Data Register Low
SP0CR1		equ $D0+MAP_PAGE	;SPI 0 Control Register 1
SP0CR2		equ $D1+MAP_PAGE	;SPI 0 Control Register 2
SP0BR		equ $D2+MAP_PAGE	;SPI 0 Baud Rate Register
SP0SR		equ $D3+MAP_PAGE	;SPI 0 Status Register
SP0DR		equ $D5+MAP_PAGE	;SPI 0 Data Register
PORTS		equ $D6+MAP_PAGE	;Port S Data Register 
DDRS		equ $D7+MAP_PAGE	;Port S Data Direction Register 
PURDS		equ $dB+MAP_PAGE	;Pullup and Reduced Drive for Port S
EEMCR		equ $F0+MAP_PAGE	;EEPROM Module Configuration Register  
EEPROT		equ $F1+MAP_PAGE	;EEPROM Block Protect
EETST		equ $F2+MAP_PAGE	;EEPROM Test Register
EEPROG		equ $F3+MAP_PAGE	;EEPROM Control Register
FEELCK		equ $F4+MAP_PAGE	;Flash EEPROM Lock Control Register
FEEMCR		equ $F5+MAP_PAGE	;Flash EEPROM Module Configuration Register
FEETST		equ $F6+MAP_PAGE	;Flash EEPROM Module Test Register
FEECTL		equ $F7+MAP_PAGE	;Flash EEPROM Control Register
BCR1		equ $F8+MAP_PAGE	;BDLC Control Register 1
BSVR		equ $F9+MAP_PAGE	;BDLC State Vector Register
BCR2		equ $FA+MAP_PAGE	;BDLC Control Register 2
BDR 		equ $FB+MAP_PAGE	;BDLC Data Register
BARD		equ $FC+MAP_PAGE	;BDLC Analog Roundtrip Delay Register
DLCSCR		equ $FD+MAP_PAGE	;Port DLC Control Register
PORTDLC		equ $FE+MAP_PAGE	;Port DLC Data Register
DDRDLC		equ $FF+MAP_PAGE	;Port DLC Data Direction Register

