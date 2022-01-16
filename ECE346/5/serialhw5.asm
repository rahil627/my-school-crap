; x86 serial communications software for homework 5


    org 100h

start: 

    ; print the starting message
    mov     ebx, test               ; sring to be displayed
    mov     ecx, testend-test       ; length of string
    call    print_string

    ; wait for keypress and get store it in al
    mov     ah, 0                   ;Read Key opcode
    int     16h
   
    ; store the COM port number
    sub     al, $31
    mov     ah, 0
    mov     [com_port],ax
    
    ; goto next line on console
    mov     al, $0D
    mov     ah, 0Eh
    int     10h
    
     ; initializes the serial port
    mov     ah, 0                   ; Initialize opcode
    mov     al, 11100011b           ; baud 9600, no parity, 1 stop bit, 8 bits
    mov     dx, [com_port]          ; port number (for COM4, enter in 3)
    int     14h 

loop:       
    ; receive a byte from serial port
    mov     dx, [com_port]          ; port number (for COM4, enter in 3)
    mov     ah, 2                   ; Receive opcode
    int     14h                     ; returns received byte in al, error status in bit 7 of ah
    
    test    ah, 10000000b           ; Check for error
    jnz     SerialError             ; if nothing was received, then dont display anything
    
    ; output character to the console
    ; al = ascii character
	cmp		al, $23
	je		end
    mov     ah, 0Eh
    int     10h
SerialError:
    ; poll the keyboard buffer to see if there are any keypresses available
    ; sets z flag is there are keypresses in the buffer
    mov     ah, 1
    int     16h
    jz      loop                    ; if no keypresses available, go back to main loop
    
    ; get next keypress from buffer
    ; al = keypress value
    mov     ah, 0                   ; Read Key opcode
    int     16h
    
    cmp     al, $1B                 ; was escape pressed?
    je      Done                    ; exit if it was
    
    jmp     loop
	
end:
	mov     ebx, enter_word_str
    mov     ecx, enter_word_str_end-enter_word_str
    
    call    print_string
	
    call    get_string
	
	mov     al, $0A
    mov     ah, 0Eh
    int     10h
	
	jmp		loop

Done:     
    mov     ah,4Ch                  ; exit to DOS
    int     21h                     ; 


; prints a string at the current cursor on the console
; inputs:
;   ebx = address to string
;   ecx = string length
print_string:
    mov     ah, 0Eh
print_string_loop:
    mov     al, [ebx]
    int     10h
    inc     ebx
    loop    print_string_loop
    ret
  
	; subroutine to get string from console and send each character to 6811 until 
	;user presses enter or max character has been reached
get_string:
	mov     dx, [com_port]          ; port number (for COM4, enter in 3)
    mov     al, 0
    mov     [characters],al
get_string_loop:
    mov     ah, 0
    int     16h
    mov     ah, 0Eh
    int     10h
    cmp     al, $0D
    je      get_string_end
	
	mov     ah, 1
    int     14h
    
    mov     ah,[characters]
    inc     ah
    mov     [characters],ah
    cmp     ah,$0f
    je      get_string_end
    jmp     get_string_loop

get_string_end:
	mov		al, $23
	mov		ah, 1
	int		14h
    ret

com_port:   dw  3
characters: dw  0
test:       db  "Enter COM port number (1-4):",$0A
testend:

enter_word_str:   db $0A, "Enter word (up to 16 characters):",$0A
enter_word_str_end:

break_str:  db "Breakpoint!"
breakend: