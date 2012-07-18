<?php

namespace WMHSim;

class Warlock extends Warrior {
    protected $fury    = 0;
    protected $curFury = 0;
    protected $transfers = 0;

    public function __construct() {
        $this->curFury = $this->fury;
    }

    public function setTransfers($transfers) {
        $this->transfers = $transfers;
    }

    public function takeDamage($result) {
        if ($result->getDamage() > 0 && $this->transfers > 0) {
            $this->transfers--;

            $this->getSim()->debug("[{$this->getName()}] transfered {$result->getDamage()}");

            return $result;
        }

        return parent::takeDamage($result);
    }

    protected function isBoostedHit() {
        $boostedHit = parent::isBoostedHit();
        if (!$boostedHit && $this->getSim()->isBoostAttack() && $this->curFury > 0) {
            $boostedHit = true;
            $this->curFury--;
        }

        return $boostedHit;
    }

    protected function isBoostedDmg() {
        $boostedDmg = parent::isBoostedDmg();
        if (!$boostedDmg && $this->getSim()->isBoostDamage() && $this->curFury > 0) {
            $boostedDmg = true;
            $this->curFury--;
        }

        return $boostedDmg;
    }

    public function attackMore(Model $defender) {
        while($this->curFury > 0) {
            $this->curFury--;
            $result = $this->doAttack($defender, reset($this->weapons));
            if ($result->isKilled() && $this->getSim()->isStopOnDeath()) {
                return;
            }
        }
    }
}
