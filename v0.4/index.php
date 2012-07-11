<?php

ini_set('display_errors', 'On');

require __DIR__.'/autoload.php';

use WMHSim\Sim;

$debug   = true;
$laps    = 1000;
$laps    = $debug ? 1 : $laps;

$attacker = new \WMHSim\Factions\Legion\Rhyas();
$defender = new \WMHSim\Factions\Cryx\Terminus();
// $defender->setTransfers(2);

$scenarios = array(
    'normal'       => array(),
    'boosted-hit'  => array('boosted-hit'),
    'boosted-dmg'  => array('boosted-dmg'),
    'boosted-both' => array('boosted-hit', 'boosted-dmg'),
);

foreach (array(true, false) as $tide) {
    echo "<hr /> {$tide} <hr />";
    foreach ($scenarios as $title => $scenario) {
        $success = 0;

        echo "<hr /> {$title} <hr />";
        for ($i = 0; $i < $laps; $i++) {
            $sim = new Sim();
            $sim->setAttacker(clone $attacker);
            $sim->setDefender(clone $defender);

            $sim->setBoostAttack(in_array('boosted-hit', $scenario));
            $sim->setBoostDamage(in_array('boosted-dmg', $scenario));
            $sim->setChargeAttack();
            $sim->setDebug($debug);

            if ($tide) {
                $sim->getAttacker()->addBuff('tide-of-blood');
            }

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
    }
}