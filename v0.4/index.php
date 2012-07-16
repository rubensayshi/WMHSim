<?php

ini_set('display_errors', 'On');
define('IS_CLI', php_sapi_name() == 'cli');
define('DEBUG',  true);

require __DIR__.'/autoload.php';

use WMHSim\SimScenario;

$laps    = 1000;
$laps    = DEBUG ? 1 : $laps;

ob_start();

$scenario = new SimScenario(new \WMHSim\Factions\Legion\Scythean(), new \WMHSim\Factions\Legion\Carnivean());

$scenario->setBoostAttack(false);
$scenario->setBoostDamage(false);
$scenario->setChargeAttack(false);
$scenario->setLaps($laps);
$scenario->setDebug(DEBUG);
// $scenario->getAttacker()->addBuff('incite');
// $scenario->getAttacker()->addBuff('chiller');
// $scenario->getAttacker()->addBuff('tide-of-blood');
// $scenario->getDefender()->addBuff('spiny-growth');
// $scenario->getDefender()->addBuff('warp-arm');

$scenario->run();

$output = ob_get_clean();
echo IS_CLI ? $output : str_replace("\n", "<br />", $output);