<?php 

namespace WMHSim;

class Sim
{
	protected $attacker;
	protected $defender;
	
	protected $totaldmg		= 0;
	protected $killed		= false;
	
	protected $chargeAttack	= false;
	protected $boostDamage	= false;
	protected $boostAttack	= false;
	
	protected $stopOnDeath	= false;
	
	public function run()
	{
		if (!$this->attacker || !$this->defender) {
			throw new \Exception('no attacker or defender');
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
	
	public function setChangeAttack($chargeAttack = false)
	{
		$this->chargeAttack = $chargeAttack;
	}
	
	public function isChargeAttack()
	{
		return $this->chargeAttack;
	}
	
	public function setBoostAttack($boostAttack = false)
	{
		$this->boostAttack = $boostAttack;
	}
	
	public function isBoostAttack()
	{
		return $this->boostAttack;
	}
	
	public function setBoostDamage($boostDamage = false)
	{
		$this->boostDamage = $boostDamage;
	}
	
	public function isBoostDamage()
	{
		return $this->boostDamage;
	}
	
	public function setStopOnDeath($stopOnDeath = false)
	{
		$this->stopOnDeath = $stopOnDeath;
	}
	
	public function isStopOnDeath()
	{
		return $this->stopOnDeath;
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