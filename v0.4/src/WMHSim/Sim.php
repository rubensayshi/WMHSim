<?php

namespace WMHSim;

class Sim
{
    protected $attacker;
    protected $defender;

    protected $totaldmg      = 0;
    protected $killed        = false;

    protected $chargeAttack  = false;
    protected $boostDamage   = false;
    protected $boostAttack   = false;

    protected $stopOnDeath   = false;

    protected $debug         = false;

    public function run() {
        if (!$this->attacker || !$this->defender) {
            throw new \Exception('no attacker or defender');
        }

        $this->attacker->attack($this->defender);
    }

    public function debug($message) {
        echoline($message);
    }

    public function setDebug($debug = true) {
        $this->debug = $debug;
    }

    public function setAttacker(Model $attacker) {
        $this->attacker = $attacker;
        $this->attacker->setSim($this);
    }

    public function setDefender(Model $defender) {
        $this->defender = $defender;
        $this->defender->setSim($this);
    }

    public function getAttacker() {
        return $this->attacker;
    }

    public function getDefender() {
        return $this->defender;
    }

    public function addDamageDone($damage) {
        $this->totaldmg += $damage;
    }

    public function getDamageDone() {
        return $this->totaldmg;
    }

    public function setKilled($killed = true) {
        $this->killed = $killed;
    }

    public function isKilled() {
        return $this->killed;
    }

    public function setChargeAttack($chargeAttack = true) {
        $this->chargeAttack = $chargeAttack;
    }

    public function isChargeAttack() {
        return $this->chargeAttack;
    }

    public function setBoostAttack($boostAttack = true) {
        $this->boostAttack = $boostAttack;
    }

    public function isBoostAttack() {
        return $this->boostAttack;
    }

    public function setBoostDamage($boostDamage = true) {
        $this->boostDamage = $boostDamage;
    }

    public function isBoostDamage() {
        return $this->boostDamage;
    }

    public function setStopOnDeath($stopOnDeath = true) {
        $this->stopOnDeath = $stopOnDeath;
    }

    public function isStopOnDeath() {
        return $this->stopOnDeath;
    }

    static public function rollDice($dices=2) {
        $diceRolls = array();
        for($i = 0; $i < $dices; $i++) {
            $diceRolls[] = rand(1,6);
        }

        $crit = false;
        $groupedRolls = array();
        foreach ($diceRolls as $roll) {
            if (isset($groupedRolls[$roll])) {
                $groupedRolls[$roll]++;
                $crit = true;
            } else {
                $groupedRolls[$roll] = 1;
            }
        }

        return array('roll' => array_sum($diceRolls), 'crit' => $crit);
    }
}