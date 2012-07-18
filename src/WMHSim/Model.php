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

    public function hasBuff($buff) {
        return in_array($buff, $this->buffs);
    }

    public function getName() { return $this->name; }
    public function getMat()  { return $this->mat;  }
    public function getStr()  { return $this->str;  }
    public function getDef()  { return $this->def;  }
    public function getArm()  { return $this->arm;  }
    public function getDmg()  { return $this->dmg;  }

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

        return $boostedDmg;
    }

    protected function attackMore(Model $defender) {
        return;
    }

    protected function doAttack(Model $defender, $weapon) {
        $result = new AttackResult();
        $result->setDead($defender->isDead());

        if (!$this->usedCharge && $this->getSim()->isChargeAttack()) {
            $result->setCharge();
            $this->usedCharge = true;
        }

        $result = $this->evalAttack($defender, $weapon, $result);
        $this->getSim()->addDamageDone($result->getDamage());
        if (!$result->isDead() && $defender->isDead()) {
            $result->setKilled();
            $this->getSim()->setKilled(true);
            $this->getSim()->debug("[{$defender->getName()} died");
        }

        return $result;

    }

    public function attack(Model $defender) {
        $this->getSim()->debug("[{$this->getName()}] starts attack on [{$defender->getName()}]");

        $chainAttack = true;

        foreach ($this->weapons as $weapon) {
            $result = $this->doAttack($defender, $weapon);
            if ($result->isKilled() && $this->getSim()->isStopOnDeath()) {
                return;
            }

            $chainAttack = $chainAttack && $result->isHit();
        }

        if ($chainAttack && method_exists($this, 'chainAttack')) {
            $this->getSim()->debug(" -- CHAIN ATTACK");
            $result = $this->chainAttack($defender);
            if ($result->isKilled() && $this->getSim()->isStopOnDeath()) {
                return;
            }
        }

        if ($this->hasBuff('tide-of-blood')) {
            $result = $this->doAttack($defender, reset($this->weapons));
            if ($result->isKilled() && $this->getSim()->isStopOnDeath()) {
                return;
            }
        }

        $this->attackMore($defender);
    }

    function evalAttack(Model $defender, $weapon, AttackResult $result) {
        $this->getSim()->debug("[{$this->getName()}] attacks [{$defender->getName()}] with {$weapon['name']}");

        $boostedHit = $this->isBoostedHit();
        $result     = $this->hitRoll($defender, $boostedHit, $result);
        if ($result->isHit()) {
            $boostedDmg = $this->isBoostedDmg();

            $dice = 2;

            if ($boostedDmg) {
                $dice += 1;
            }
            if (isset($weapon['weapon-master']) && $weapon['weapon-master']) {
                $dice += 1;
            }

            $result = $this->damageRoll($defender, $weapon['pow'], $dice, $result);

            if (isset($weapon['crit-decap']) && $weapon['crit-decap'] && $result->isCrit()) {
                $result->setDamage($result->getDamage() * 2);
            }

            $result = $defender->takeDamage($result);
        }

        return $result;
    }

    public function takeDamage(AttackResult $result) {
        $this->getSim()->debug("[{$this->getName()}] took {$result->getDamage()} dmg");

        $this->curDmg += $result->getDamage();

        if (!$result->isDead() && $this->isDead()) {
            $result->setKilled();
        }

        return $result;
    }

    function hitRoll(Model $defender, $boosted=false, AttackResult $result) {
        if ($boosted) {
            $rollTxt    = '3D6';
            $roll        = Sim::rollDice(3);
        } else {
            $rollTxt    = '2D6';
            $roll        = Sim::rollDice(2);
        }

        $result->setCrit($roll['crit']);
        $roll = $roll['roll'];

        $off = $this->getMat() + $roll;

        if ($this->hasBuff('incite')) {
            $off += 2;
        }
        if ($this->hasBuff('chiller')) {
            $off += 2;
        }

        $def = $defender->getDef();
        $result->setHit((boolean)($off >= $def));

        $this->getSim()->debug("{MAT {$this->getMat()} + roll {$roll} ({$rollTxt}) = {$off} VS def {$def} ".($result->isHit() ? ($result->isCrit() ? 'crit' : 'hit') : 'missed')." [{$defender->getName()}]");

        return $result;
    }

    function damageRoll(Model $defender, $pow=0, $dice=2, AttackResult $result) {
        $rollTxt = "{$dice}D6";
        $roll    = Sim::rollDice($dice);

        $roll = $roll['roll'];

        $dmg = $this->getStr() + $pow + $roll;
        $arm = $defender->getArm();

        if ($this->hasBuff('incite')) {
            $dmg += 2;
        }

        if ($defender->hasBuff('warp-arm')) {
            $arm += 2;
        }

        if ($defender->hasBuff('spiny-growth')) {
            $arm += 2;
        }

        $res = $dmg - $arm;

        $this->getSim()->debug("[{$defender->getName()}] (P {$pow} + S {$this->getStr()} + roll {$roll} ({$rollTxt}) = {$dmg} - {$arm})");

        $result->setDamage($res > 0 ? $res : 0);

        return $result;
    }

}