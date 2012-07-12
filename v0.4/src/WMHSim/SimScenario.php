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
        $dmg     = array();

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

            $dmg[] = $sim->getDamageDone();

            if ($sim->isKilled()) {
                $success++;
            }

            if ($this->debug) {
                echo "-------\n";
            }
        }

        sort($dmg);

        $chance = round(($success / $this->laps) * 100, 2);
        $avgdmg = round(array_sum($dmg) / $this->laps, 2);
        $avgofhp= round(($avgdmg / $sim->getDefender()->getDmg()) * 100, 2);
        $median = round(self::median($dmg), 2);
        $medofhp= round(($median / $sim->getDefender()->getDmg()) * 100, 2);

        echo "{$this->laps} laps, {$success} kills = {$chance}% chance, {$avgdmg} avg damage ({$avgofhp}%), {$median} median damage ({$medofhp}%)\n";
    }

    public static function median($vals) {
        $n = count($vals);
        $h = intval($n / 2);

        if ($n == 1) {
            return $vals[0];
        }

        if($n % 2 == 0) {
            $median = ($vals[$h] + $vals[$h-1]) / 2;
        } else {
            $median = $vals[$h];
        }

        return $median;
    }
}