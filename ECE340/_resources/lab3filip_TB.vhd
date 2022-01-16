-------------------------------------------------------------------------------
--
-- Title       : Test Bench for lab3filip
-- Design      : lab3filip
-- Author      : BCET
-- Company     : ODU
--
-------------------------------------------------------------------------------
--
-- File        : $DSN\src\TestBench\lab3filip_TB.vhd
-- Generated   : 11/7/2008, 8:12 AM
-- From        : $DSN\src\lab3filip.vhd
-- By          : Active-HDL Built-in Test Bench Generator ver. 1.2s
--
-------------------------------------------------------------------------------
--
-- Description : Automatically generated Test Bench for lab3filip_tb
--
-------------------------------------------------------------------------------

library ieee;
use ieee.std_logic_1164.all;

	-- Add your library and packages declaration here ...

entity lab3filip_tb is
end lab3filip_tb;

architecture TB_ARCHITECTURE of lab3filip_tb is
	-- Component declaration of the tested unit
	component lab3filip
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
	UUT : lab3filip
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

	-- Add your stimulus here ...

end TB_ARCHITECTURE;

configuration TESTBENCH_FOR_lab3filip of lab3filip_tb is
	for TB_ARCHITECTURE
		for UUT : lab3filip
			use entity work.lab3filip(lab3filip);
		end for;
	end for;
end TESTBENCH_FOR_lab3filip;

