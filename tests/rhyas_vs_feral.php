<?php

ini_set('display_errors', 'On');
define('IS_CLI', php_sapi_name() == 'cli');
define('DEBUG',  false);

require __DIR__.'/../autoload.php';

use WMHSim\SimScenario;

$laps    = 1000;
$laps    = DEBUG ? 1 : $laps;

ob_start();

$scenario = new SimScenario(new \WMHSim\Factions\Legion\Rhyas(), new \WMHSim\Factions\Circle\FeralWarpwolf());

$scenario->setBoostAttack(true);
$scenario->setBoostDamage(true);
$scenario->setChargeAttack(false);
$scenario->setLaps($laps);
$scenario->setDebug(DEBUG);
$scenario->getDefender()->addBuff('warp-arm');

$scenario->run();

$output = ob_get_clean();
echo IS_CLI ? $output : str_replace("\n", "<br />", $output);