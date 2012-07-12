<?php

namespace WMHSim;

class Model {
    protected $sim;
    protected $name = 'Dummy';

    protected $str = 0;
    protected $def = 0;
    protected $arm = 0;
    protected $mat = 0;
    protected $dmg = 0;

    protected $curDmg  = 0;
    protected $weapons = array();
    protected $buffs   = array();

    protected $usedCharge = false;

    public function setSim(SimOptions $sim) {
        $this->sim = $sim;
    }

    /**
     *
     * @return WMHSim\Sim
     */
    protected function getSim() {
        return $this->sim;
    }

    public function addBuff($buff) {
        $this->buffs[] = $buff;
    }

    public function getName() { return $this->name; }
    public function getMat()  { return $this->mat;  }
    public function getStr()  { return $this->str;  }
    public function getDef()  { return $this->def;  }
    public function getArm()  { return $this->arm;  }

    public function isDead() {
        return ($this->curDmg >= $this->dmg);
    }

    protected function isBoostedHit() {
        $boostedHit = false;

        return $boostedHit;
    }

    protected function isBoostedDmg() {
        $boostedDmg = false;

        if ($this->getSim()->isChargeAttack() && !$this->usedCharge) {
            $boostedDmg = true;
            $this->usedCharge = true;
        }

        return false;
    }

    protected function attackMore(Model $defender) {
        return;
    }

    protected function doAttack(Model $defender, $weapon) {
        $isDead = $defender->isDead();

        $this->getSim()->addDamageDone($this->evalAttack($defender, $weapon));
        if (!$isDead && ($isDead = $defender->isDead())) {
            $this->getSim()->setKilled(true);
            $this->getSim()->debug("[{$defender->getName()} died");

            if ($this->getSim()->isStopOnDeath()) {
                return true;
            }
        }

        return false;

    }

    public function attack(Model $defender) {
        $this->getSim()->debug("[{$this->getName()}] starts attack on [{$defender->getName()}]");

        foreach ($this->weapons as $weapon) {
            if ($this->doAttack($defender, $weapon)) {
                return;
            }
        }

        if (in_array('tide-of-blood', $this->buffs)) {
            if ($this->doAttack($defender, reset($this->weapons))) {
                return;
            }
        }

        $this->attackMore($defender);
    }

    function evalAttack(Model $defender, $weapon) {
        $this->getSim()->debug("[{$this->getName()}] attacks [{$defender->getName()}] with {$weapon['name']}");

        $boostedHit = $this->isBoostedHit();

        if (($res = $this->hitRoll($defender, $boostedHit)) && $res['hit']) {
            $boostedDmg = $this->isBoostedDmg();

            $dice = 2;

            if ($boostedDmg) {
                $dice += 1;
            }
            if (isset($weapon['weapon-master']) && $weapon['weapon-master']) {
                $dice += 1;
            }

            $damageDone = $this->damageRoll($defender, $weapon['pow'], $dice);

            if (isset($weapon['crit-decap']) && $weapon['crit-decap'] && $res['crit']) {
                $damageDone *= 2;
            }

            $defender->takeDamage($damageDone);

            return $damageDone;
        }
    }

    public function takeDamage($damageDone) {
        $this->getSim()->debug("[{$this->getName()}] took {$damageDone} dmg");

        $this->curDmg += $damageDone;
    }

    function hitRoll(Model $defender, $boosted=false) {
        if ($boosted) {
            $rollTxt    = '3D6';
            $roll        = Sim::rollDice(3);
        } else {
            $rollTxt    = '2D6';
            $roll        = Sim::rollDice(2);
        }

        $crit = $roll['crit'];
        $roll = $roll['roll'];

        $off = $this->getMat() + $roll;
        $def = $defender->getDef();
        $res = $off >= $def;

        $this->getSim()->debug("{MAT {$this->getMat()} + roll {$roll} ({$rollTxt}) = {$off} VS def {$def} ".($res ? ($crit ? 'crit' : 'hit') : 'missed')." [{$defender->getName()}]");

        return array('hit' => $res, 'crit' => $crit);
    }

    function damageRoll(Model $defender, $pow=0, $dice=2) {
        $rollTxt = "{$dice}D6";
        $roll    = Sim::rollDice($dice);

        $roll = $roll['roll'];
        $crit = $roll['crit'];

        $dmg = $this->getStr() + $pow + $roll;
        $arm = $defender->getArm();
        $res = $dmg - $arm;

        $this->getSim()->debug("[{$defender->getName()}] (P {$pow} + S {$this->getStr()} + roll {$roll} ({$rollTxt}) = {$dmg} - {$arm})");

        return $res > 0 ? $res : 0;
    }

}