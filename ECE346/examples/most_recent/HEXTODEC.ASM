	ORG $0
FD	RMB 1
SD	RMB 1
TD	RMB 1

	ORG $10
DAT	RMB 1

	ORG $100

	LDAB	DAT	;CHANGE THIS VALUE TO TEST


	CLRA
	LDX	#100	
	IDIV		;DIVIDE BY 100, RESULT IN X, REMAINDER IN D
	XGDX		;REMAINDER IS IN X NOW
	STAB	FD	;FD IS THE FIRST DIGIT	

	XGDX	;REMAINDER IS IN D NOW

	LDX	#10
	IDIV		;DIVIDE BY 10, RESULT IN X, REMAINDER IN D
	XGDX		;REMAINDER IS IN X NOW
	STAB	SD	;SD IS THE SECOND DIGIT		

	XGDX	;REMAINDER IS IN D NOW

	STAB	TD	;TD IS THE THIRD DIGIT



	SWI