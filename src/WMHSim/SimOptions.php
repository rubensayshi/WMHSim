<?php

namespace WMHSim;

class SimOptions {
    protected $attacker;
    protected $defender;

    protected $chargeAttack  = false;
    protected $boostDamage   = false;
    protected $boostAttack   = false;

    protected $stopOnDeath   = false;

    protected $debug         = false;

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

    /**
     *
     * @return Model
     */
    public function getAttacker() {
        return $this->attacker;
    }

    /**
     *
     * @return Model
     */
    public function getDefender() {
        return $this->defender;
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
}