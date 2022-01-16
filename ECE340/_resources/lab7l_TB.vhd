-------------------------------------------------------------------------------
--
-- Title       : Test Bench for lab7l
-- Design      : lab7l
-- Author      : BCET
-- Company     : ODU
--
-------------------------------------------------------------------------------
--
-- File        : $DSN\src\TestBench\lab7l_TB.vhd
-- Generated   : 11/18/2008, 7:52 PM
-- From        : $DSN\compile\lab7l.vhd
-- By          : Active-HDL Built-in Test Bench Generator ver. 1.2s
--
-------------------------------------------------------------------------------
--
-- Description : Automatically generated Test Bench for lab7l_tb
--
-------------------------------------------------------------------------------

library ieee;
use ieee.std_logic_unsigned.all;
use ieee.std_logic_arith.all;
use ieee.std_logic_1164.all;

	-- Add your library and packages declaration here ...

entity lab7l_tb is
end lab7l_tb;

architecture TB_ARCHITECTURE of lab7l_tb is
	-- Component declaration of the tested unit
	component lab7l
	port(
		CLK : in std_logic;
		D : in std_logic;
		N : in std_logic;
		Q : in std_logic;
		C : out std_logic;
		P : out std_logic );
	end component;

	-- Stimulus signals - signals mapped to the input and inout ports of tested entity
	signal CLK : std_logic;
	signal D : std_logic;
	signal N : std_logic;
	signal Q : std_logic;
	-- Observed signals - signals mapped to the output ports of tested entity
	signal C : std_logic;
	signal P : std_logic;

	-- Add your code here ...

begin

	-- Unit Under Test port map
	UUT : lab7l
		port map (
			CLK => CLK,
			D => D,
			N => N,
			Q => Q,
			C => C,
			P => P
		);

	-- Add your stimulus here ...


end TB_ARCHITECTURE;

configuration TESTBENCH_FOR_lab7l of lab7l_tb is
	for TB_ARCHITECTURE
		for UUT : lab7l
			use entity work.lab7l(lab7l);
		end for;
	end for;
end TESTBENCH_FOR_lab7l;

