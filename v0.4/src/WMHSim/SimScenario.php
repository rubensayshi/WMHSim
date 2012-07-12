<?php

namespace WMHSim;

class SimScenario extends SimOptions {

    protected $laps = 1;

    public function __construct(Model $attacker, Model $defender) {
        $this->setAttacker($attacker);
        $this->setDefender($defender);
    }

    public function setLaps($laps) {
        if ($laps > 0) {
            $this->laps = $laps;
        }
    }

    public function run() {
        $success = 0;
        $dmg     = 0;

        for ($i = 0; $i < $this->laps; $i++) {
            $sim = new Sim();
            $sim->setAttacker(clone $this->attacker);
            $sim->setDefender(clone $this->defender);

            $sim->setBoostAttack($this->isBoostAttack());
            $sim->setBoostDamage($this->isBoostDamage());
            $sim->setChargeAttack($this->isChargeAttack());
            $sim->setStopOnDeath($this->isStopOnDeath());
            $sim->setDebug($this->debug);

            $sim->run();

            $dmg += $sim->getDamageDone();

            if ($sim->isKilled()) {
                $success++;
            }

            if ($this->debug) {
                echo "-------\n";
            }
        }

        $chance = round(($success / $this->laps) * 100, 2);
        $avgdmg = round($dmg / $this->laps, 2);
        $avgofhp= round(($avgdmg / $sim->getDefender()->getDmg()) * 100, 2);

        echo "{$this->laps} laps, {$success} kills = {$chance}% chance, {$avgdmg} avg damage ({$avgofhp}% of total hp)\n";
    }
}