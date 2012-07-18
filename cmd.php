<?php

ini_set('display_errors', 'On');
define('IS_CLI', php_sapi_name() == 'cli');
define('DEBUG',  in_array('--debug', $argv));

require __DIR__.'/autoload.php';

use WMHSim\Warlock;
use WMHSim\SimScenario;

$laps    = 5000;
$laps    = DEBUG ? 1 : $laps;

ob_start();

$attacker = str_replace("::", "\\", "\\WMHSim\\Factions\\{$argv[1]}");
$defender = str_replace("::", "\\", "\\WMHSim\\Factions\\{$argv[2]}");

$scenario = new SimScenario(new $attacker, new $defender);

$scenario->setBoostAttack((bool)array_intersect(array('--boosted-hit', 'boosted-both', '--boost-hit', '--boost-both'), $argv));
$scenario->setBoostDamage((bool)array_intersect(array('--boosted-dmg', 'boosted-both', '--boost-dmg', '--boost-both'), $argv));
$scenario->setChargeAttack((bool)array_intersect(array('--charge', '--charge-attack'), $argv));
$scenario->setLaps($laps);
$scenario->setDebug(DEBUG);

if (array_intersect(array('--incite'), $argv)) {
    $scenario->getAttacker()->addBuff('incite');
}
if (array_intersect(array('--chiller'), $argv)) {
    $scenario->getAttacker()->addBuff('chiller');
}
if (array_intersect(array('--warp-str'), $argv)) {
    $scenario->getAttacker()->addBuff('warp-str');
}
if (array_intersect(array('--tide-of-blood'), $argv)) {
    $scenario->getAttacker()->addBuff('tide-of-blood');
}
if (array_intersect(array('--warp-arm'), $argv)) {
    $scenario->getDefender()->addBuff('warp-arm');
}
if (array_intersect(array('--spiny-growth'), $argv)) {
    $scenario->getDefender()->addBuff('spiny-growth');
}

if ($scenario->getDefender() instanceof Warlock) {
    if (($k = array_search('--transfers', $argv)) !== false) {
        if (!isset($argv[$k+1]) || !is_numeric($argv[$k+1])) {
            throw new Exception("Specfied --transfer but without the amount of transfers");
        }

        $scenario->getDefender()->setTransfers($argv[$k+1]);
    }
}

$scenario->run();

$output = ob_get_clean();
echo IS_CLI ? $output : str_replace("\n", "<br />", $output);