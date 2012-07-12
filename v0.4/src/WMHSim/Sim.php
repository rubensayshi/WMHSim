<?php

namespace WMHSim;

class Sim extends SimOptions {
    protected $totaldmg      = 0;
    protected $killed        = false;

    public function run() {
        if (!$this->attacker || !$this->defender) {
            throw new \Exception('no attacker or defender');
        }

        $this->attacker->attack($this->defender);
    }

    public function debug($message) {
        if ($this->debug) {
            echo "{$message}\n";
        }
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