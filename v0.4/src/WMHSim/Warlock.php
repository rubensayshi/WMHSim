<?php

namespace WMHSim;

class Warlock extends Warrior {
    protected $fury    = 0;
    protected $curFury = 0;

    public function __construct() {
        $this->curFury = $this->fury;
    }

    protected function isBoostedHit() {
        $boostedHit = parent::isBoostedHit();
        if (!$boostedHit && $this->getSim()->isBoostAttack() && $this->curFury > 0) {
            $boostedHit = true;
            $this->curFury--;
        }

        return $boostedHit;
    }

    public function attackMore($defender) {
        while($this->curFury > 0) {
            $this->curFury--;
            if ($this->doAttack($defender, reset($this->weapons))) {
                return;
            }
        }
    }
}
