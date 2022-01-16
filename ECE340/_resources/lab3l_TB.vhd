-------------------------------------------------------------------------------
--
-- Title       : Test Bench for lab3l
-- Design      : lab3l
-- Author      : BCET
-- Company     : ODU
--
-------------------------------------------------------------------------------
--
-- File        : $DSN\src\TestBench\lab3l_TB.vhd
-- Generated   : 9/30/2008, 8:17 PM
-- From        : $DSN\src\lab3l.vhd
-- By          : Active-HDL Built-in Test Bench Generator ver. 1.2s
--
-------------------------------------------------------------------------------
--
-- Description : Automatically generated Test Bench for lab3l_tb
--
-------------------------------------------------------------------------------

library ieee;
use ieee.std_logic_1164.all;

	-- Add your library and packages declaration here ...

entity lab3l_tb is
end lab3l_tb;

architecture TB_ARCHITECTURE of lab3l_tb is
	-- Component declaration of the tested unit
	component lab3l
	port(
		A : in std_logic;
		B : in std_logic;
		C : in std_logic;
		D : in std_logic;
		Ya : out std_logic;
		Yb : out std_logic;
		Yc : out std_logic;
		Yd : out std_logic;
		Ye : out std_logic;
		Yf : out std_logic;
		Yg : out std_logic );
	end component;

	-- Stimulus signals - signals mapped to the input and inout ports of tested entity
	signal A : std_logic;
	signal B : std_logic;
	signal C : std_logic;
	signal D : std_logic;
	-- Observed signals - signals mapped to the output ports of tested entity
	signal Ya : std_logic;
	signal Yb : std_logic;
	signal Yc : std_logic;
	signal Yd : std_logic;
	signal Ye : std_logic;
	signal Yf : std_logic;
	signal Yg : std_logic;

	-- Add your code here ...

begin

	-- Unit Under Test port map
	UUT : lab3l
		port map (
			A => A,
			B => B,
			C => C,
			D => D,
			Ya => Ya,
			Yb => Yb,
			Yc => Yc,
			Yd => Yd,
			Ye => Ye,
			Yf => Yf,
			Yg => Yg
		);

D <= '0', '1' after 10ns, '0' after 20ns, '1' after 30ns, '0' after 40ns,
'1' after 50ns, '0' after 60ns, '1' after 70ns, '0' after 80ns, '1' after
90ns, '0' after 100ns, '1' after 110ns, '0' after 120ns, '1' after 130ns,
'0' after 140ns, '1' after 150ns;
C <= '0', '1' after 20ns, '0' after 40ns, '1' after 60ns, '0' after 80ns,
'1' after 100ns, '0' after 120ns, '1' after 140ns;
B <= '0', '1' after 40ns, '0' after 80ns, '1' after 120ns;
A <= '0', '1' after 80ns;


end TB_ARCHITECTURE;

configuration TESTBENCH_FOR_lab3l of lab3l_tb is
	for TB_ARCHITECTURE
		for UUT : lab3l
			use entity work.lab3l(lab3l);
		end for;
	end for;
end TESTBENCH_FOR_lab3l;

