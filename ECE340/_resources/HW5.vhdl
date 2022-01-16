--1a
-- a structural VHDL description of a 1-to-4 demultiplexer using INVERTER and AND gates
library ieee, lcdf_vhdl;
use ieee.std_logic_1164.all, lcdf_vhdl.func_prims.all;

entity demultiplexer_1to4 is
	port
	(
		I, S0, S1: in std_logic;
		Y0, Y1, Y2, Y3: out std_logic
	);
end demultiplexer_1to4;

architecture structural_demultiplexer_1to4 of demultiplexer_1to4  is
	component INV1
		port
		(
			in1: in std_logic;
			out1: out std_logic
		);
	end component;

	component AND3
		port
		(
			in1, in2, in3: in std_logic;
			out1: out std_logic
		);
	end component;

	signal X0_n, X1_n: std_logic;
	
	begin
	G1: INV1 port map (in1 => S0, out1 => S0_b);
	G2: INV1 port map (in1 => S1, out1 => S1_b);
	G3: AND3 port map (in1 => S0, in2 => S1, in3 => I, out1 => Y3);
	G4: AND3 port map (in1 => S0, in2 => S1_n, in3 => I, out1 => Y2);
	G5: AND3 port map (in1 => S0_n, in2 => S1, in3 => I, out1 => Y1);
	G6: AND3 port map (in1 => S0_n, in2 => S1_n, in3 => I, out1 => Y0);
end structural_demultiplexer_1to4;

--1b
-- a functional VHDL description of a 1-to-4 demultiplexer
library ieee;
use ieee.std_logic_1164.all;

entity demultiplexer_1to4  is
	port
	( 
		I: in std_logic;
		sel: in std_logic_vector (1 downto 0);
		Y0, Y1, Y2, Y3: out std_logic
	);
end demultiplexer_1to4;

architecture functional_demultiplexer_1to4 of demultiplexer_1to4  is
	begin process(sel,X)
		begin case sel is
			when "00"=>
			Y0 <=X;
			Y1 <= '0';
			Y2 <= '0';
			Y3 <= '0';
			when "01" =>
			Y0 <= '0';
			Y1 <=X;
			Y2 <= '0';
			Y3 <= '0';
			when "10" =>
			Y0 <= '0';
			Y1 <= '0';
			Y2 <=X;
			Y3 <= '0';
			when others =>
			Y0 <= '0';
			Y1 <= '0';
			Y2 <= '0';
			Y3 <=X;
		end case;
	end process;
end functional_demultiplexer_1to4;


--2a
-- a structural VHDL description of a 4-to-2 priority encoder
library ieee, lcdf_vhdl;
use ieee.std_logic_1164.all, lcdf_vhdl.func_prims.all;

entity demultiplexer_1to4 is
	port
	(
		i0, i1, i2, i3: in std_logic;
		o0, o1, p: out std_logic
	);
end demultiplexer_1to4;

architecture structural_demultiplexer_1to4 of demultiplexer_1to4  is
	component INV1
		port
		(
			in1: in std_logic;
			out1: out std_logic
		);
	end component;

	component AND2
		port
		(
			in1, in2: in std_logic;
			out1: out std_logic
		);
	end component;
	
	component OR2
		port
		(
			in1, in2: in std_logic;
			out1: out std_logic
		);
	end component;
	
	component OR4
		port
		(
			in1, in2, in3, in4: in std_logic;
			out1: out std_logic
		);
	end component;

	signal i2_n, i2_nANDi1_n: std_logic;
	
	begin
	G1: INV1 port map (i2,i2_b);
	G3: AND2 port map (i2_n,i1,i2_nANDi1_b);
	G4: OR3 port map (i2, i3, o1);
	G5: OR3 port map (i3,i2_nANDi1_n,o0);
	G6: OR3 port map (i0,i1,i2,i3,p);
end structural_demultiplexer_1to4;

--2b
-- a functional VHDL description of a 4-to-2 priority encoder
LIBRARY ieee ;
USE ieee.std_logic_1164.all;

ENTITY priority IS
PORT
(
	i: IN STD_LOGIC_VECTOR(3 DOWNTO 0) ;
	o: OUT STD_LOGIC_VECTOR(1 DOWNTO 0) ;
	p: OUT STD_LOGIC
);
END priority ;

ARCHITECTURE Behavior OF priority IS
BEGIN
	o <=
	"11" WHEN i(3) = '1' ELSE
	"10" WHEN i(2) = '1' ELSE
	"01" WHEN i(1) = '1' ELSE
	"00" ;
	p <= '0' WHEN w = "0000" ELSE '1' ;
END Behavior;