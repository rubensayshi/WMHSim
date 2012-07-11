<?php

require __DIR__.'/autoload.php';

use WMHSim\Factions\Example\ExampleBeast;
use WMHSim\Factions\Legion\Rhyas;
use WMHSim\Sim;

$debug   = true;
$laps    = 1000;
$laps    = $debug ? 1 : $laps;
$success = 0;

$attacker = new Rhyas();
$defender = new ExampleBeast();

for ($i = 0; $i < $laps; $i++) {
    $sim = new Sim();
    $sim->setAttacker(clone $attacker);
    $sim->setDefender(clone $defender);

    $sim->setBoostAttack();
    $sim->setChangeAttack();
    $sim->setDebug($debug);

    $sim->run();

    if ($sim->isKilled()) {
        $success++;
    }

    if ($debug) {
        echo "---\n";
    }
}

$chance = round($success / $laps * 100, 2);

echo "{$laps} laps, {$success} kills = {$chance}% chance \n";