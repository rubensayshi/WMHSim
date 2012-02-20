<?php 

namespace WMHSim;

class Sim
{
	protected $attacker;
	protected $defender;
	
	protected $totaldmg;
	protected $killed;

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
	
	public function addDamageDone($damage)
	{
		$this->totaldmg += $damage;
	}
	
	public function getDamageDone()
	{
		return $this->totaldmg;
	}
	
	public function setKilled($killed = true)
	{
		$this->killed = $killed;
	}
	
	public function isKilled()
	{
		return $this->killed;
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