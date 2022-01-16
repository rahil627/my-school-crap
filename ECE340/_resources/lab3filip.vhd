library IEEE;
use IEEE.STD_LOGIC_1164.all;

entity lab3filip is
	 port(
		 A : in STD_LOGIC;
		 B : in STD_LOGIC;
		 C : in STD_LOGIC;
		 D : in STD_LOGIC;
		 Ya : out STD_LOGIC;
		 Yb : out STD_LOGIC;
		 Yc : out STD_LOGIC;
		 Yd : out STD_LOGIC;
		 Ye : out STD_LOGIC;
		 Yf : out STD_LOGIC;
		 Yg : out STD_LOGIC
	     );
end lab3filip;

--}} End of automatically maintained section

architecture lab3filip of lab3filip is
begin

	-- F(ABCD)= 'A C  +  B C  +  'B' D  +  'A B D  +   A 'B 'C  
											  
	Ya <= ((not A) and C) or (B and C ) or ((not B) AnD (not D)) or ((not A) and B AND D) or (A and (not B) and (not C));
    Yb <= (A and (not B)) or (A and C) or ((not A) and B and (not D)) or ((not A) and (not C) and (not D));
    Yc <= (A and B) or (C and (not D)) or (A and C) or (A and (not D)) or ((not B) and (not D));
    Yd <= ((not A) and (not B) and (not D)) or (A and B and (not D)) or ((not A) and C and (not D)) or (B and (not C) and D) or (A and (not C) and (not D)) or ((not B) and C and D);
    Ye <= ((not A) and B) or (A and (not B)) or ((not C) and D) or ((not A) and (not C)) or ((not A) and D);
    Yf <= ((not B) and (not C)) or ((not B) and (not D)) or ((not A) and (not C) and (not D)) or ((not A) and C and D) or (A and D and (not C));
    Yg <= (A) or (C and (not D)) or (B and (not C)) or ((not B) and C);

	
end lab3filip;
