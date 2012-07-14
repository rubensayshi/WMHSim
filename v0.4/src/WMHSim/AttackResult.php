<?php

namespace WMHSim;

class AttackResult {
    protected $dead    = false;
    protected $charge  = false;
    protected $hit     = false;
    protected $crit    = false;
    protected $damage  = 0;
    protected $killed  = false;

    public static function newInstance() {
        return new self();
    }

    public function isDead() {
        return $this->dead;
    }

    public function setDead($dead = true) {
        $this->dead = $dead;

        return $this;
    }

    public function isCharge() {
        return $this->charge;
    }

    public function setCharge($charge = true) {
        $this->charge = $charge;

        return $this;
    }

    public function isHit() {
        return $this->hit;
    }

    public function setHit($hit = true) {
        $this->hit = $hit;

        return $this;
    }

    public function isCrit() {
        return $this->crit;
    }

    public function setCrit($crit = true) {
        $this->crit = $crit;

        return $this;
    }

    public function getDamage() {
        return $this->damage;
    }

    public function setDamage($damage = 0) {
        $this->damage = $damage;

        return $this;
    }

    public function isKilled() {
        return $this->killed;
    }

    public function setKilled($killed = true) {
        $this->killed = $killed;

        return $this;
    }

}