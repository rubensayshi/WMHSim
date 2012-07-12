<?php

ini_set('display_errors', 'On');
define('IS_CLI',  php_sapi_name() == 'cli');
define('DEBUG',   false);

function echoline($s, $debug=true) {
    if ($debug && !DEBUG) {
        return;
    }

    if (is_bool($s)) {
        $s = $s ? 'true' : 'false';
    }

    echo $s . (IS_CLI ? "\n" : "<br />");
}
function echoheader($s, $debug=false) {
    if ($debug && !DEBUG) {
        return;
    }

    if (is_bool($s)) {
        $s = $s ? 'true' : 'false';
    }

    if (IS_CLI) {
        echo "\n===================\n{$s}\n===================\n";
    } else {
        echo "<hr />{$s}<hr />";

    }
}

require __DIR__.'/autoload.php';

use WMHSim\Sim;

$laps    = 1000;
$laps    = DEBUG ? 1 : $laps;

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
    echoheader($tide, false);
    foreach ($scenarios as $title => $scenario) {
        $success = 0;

        echoheader($title, false);
        for ($i = 0; $i < $laps; $i++) {
            $sim = new Sim();
            $sim->setAttacker(clone $attacker);
            $sim->setDefender(clone $defender);

            $sim->setBoostAttack(in_array('boosted-hit', $scenario));
            $sim->setBoostDamage(in_array('boosted-dmg', $scenario));
            $sim->setChargeAttack();
            $sim->setDebug(DEBUG);

            if ($tide) {
                $sim->getAttacker()->addBuff('tide-of-blood');
            }

            $sim->run();

            if ($sim->isKilled()) {
                $success++;
            }

            echoline("-------");
        }

        $chance = round($success / $laps * 100, 2);

        echoline("{$laps} laps, {$success} kills = {$chance}% chance", false);
    }
}