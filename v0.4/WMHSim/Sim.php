<?php 

namespace WMHSim;

class Sim
{
	protected $attacker;
	protected $defender;

	public function run()
	{
		if (!$this->attacker || !$this->defender) {
			die('no attacker or defender');
		}
		
		$this->attacker->attack($this->defender);
	}
	
	public function debug($message) 
	{
		echo "{$message}\n";	
	}
	
	public function setAttacker(Model $attacker)
	{
		$this->attacker = $attacker;
		$this->attacker->setSim($this);
	}
	
	public function setDefender(Model $defender)
	{
		$this->defender = $defender;
		$this->defender->setSim($this);
	}

	static public function rollDice($dices=2)
	{
		$diceRolls = array();
		for($i = 0; $i < $dices; $i++)
		{
			$diceRolls[] = rand(1,6);
		}
		
		return array_sum($diceRolls);	
	}
}