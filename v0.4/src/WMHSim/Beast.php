<?php 

namespace WMHSim;

class Beast extends Model
{
	protected $sim;
	protected $name = 'Dummy';
	
	protected $str = 0;
	protected $def = 0;
	protected $arm = 0;
	protected $mat = 0;
	protected $dmg = 0;
	protected $fury = 0;
	
	protected $curFury	= 0;
	protected $curDmg	= 0;
	protected $weapons = array();
	protected $buffs = array();
		
	public function setSim($sim) 
	{
		$this->sim = $sim;
	}
	
	/**
	 * 
	 * @return WMHSim\Sim
	 */
	protected function getSim()
	{
		return $this->sim;
	}
	
	public function getName() {			return $this->name;	}
	public function getMat() {			return $this->mat; }
	public function getStr() {			return $this->str; }
	public function getDef() {			return $this->def; }
	public function getArm() {			return $this->arm; }
	
	public function isDead() {
		return ($this->curDmg >= $this->dmg);
	}

	public function attack($defender)
	{
		$this->getSim()->debug("[{$this->getName()}] starts attack on [{$defender->getName()}]");
		$isDead			= false;
		
		foreach ($this->weapons as $weapon) {
			$this->getSim()->addDamageDone($this->evalAttack($defender, $weapon));
			if (!$isDead && ($isDead = $defender->isDead())) {
				$this->getSim()->setKilled(true);
				$this->getSim()->debug("[{$defender->getName()} died");

				if ($this->getSim()->isStopOnDeath()) {
					return $defender->getDmg();
				}
			}
		}
		
		while($this->curFury < $this->fury) {
			$this->curFury++;
			$this->getSim()->addDamageDone($this->evalAttack($defender, reset($this->weapons)));
			if (!$isDead && ($isDead = $defender->isDead())) {
				$this->getSim()->setKilled(true);
				$this->getSim()->debug("[{$defender->getName()} died");
				
				if ($this->getSim()->isStopOnDeath()) {
					return $defender->getDmg();
				}
			}		
		}
	}
	
	function evalAttack($defender, $weapon)
	{	
		$this->getSim()->debug("[{$this->getName()}] attacks [{$defender->getName()}] with {$weapon['name']}");
		
		$boostedHit = false;
		if ($this->getSim()->isBoostAttack() && $this->curFury < $this->fury) {
			$boostedHit = true;
			$this->curFury++;
		}
		
		if ($this->hitRoll($defender, $boostedHit)) {		
			$boostedDmg = false;
			if ($this->getSim()->isBoostDamage() && $this->curFury < $this->fury) {
				$boostedDmg = true;
				$this->curFury++;
			}
			$damageDone = $this->damageRoll($defender, $weapon['pow'], $boostedDmg);
			
			$defender->takeDamage($damageDone);

			return $damageDone;
		}
	}
	
	public function takeDamage($damageDone)
	{
		$this->curDmg += $damageDone;
	}
	
	function hitRoll($defender, $boosted=false)
	{
		if ($boosted) {
			$rollTxt	= '3D6';
			$roll		= Sim::rollDice(3);		
		} else {
			$rollTxt	= '2D6';
			$roll		= Sim::rollDice(2);
		}
		
		$off = $this->getMat() + $roll;
		$def = $defender->getDef();
		$res = $off >= $def;
		
		$this->getSim()->debug("[{$this->getName()}] {MAT {$this->getMat()} + roll {$roll} ({$rollTxt}) = {$off} VS def {$def} ".($res ? 'hit' : 'missed')." [{$defender->getName()}]");
			
		return $res;
	}
	
	function damageRoll($defender, $pow=0, $boosted=false)
	{
		if ($boosted) {
			$rollTxt	= '3D6';
			$roll		= Sim::rollDice(3);		
		} else {
			$rollTxt	= '2D6';
			$roll		= Sim::rollDice(2);
		}
		
		$dmg = $this->getStr() + $pow + $roll;
		$arm = $defender->getArm();
		$res = $dmg - $arm;

		$this->getSim()->debug("[{$defender->getName()}] took {$res} (P {$pow} + S {$this->getStr()} + roll {$roll} = {$dmg} - {$arm})");
		
		return $res > 0 ? $res : 0;
	}
}
