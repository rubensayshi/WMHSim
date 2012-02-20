<?php

use WMHSim\Factions\Example\ExampleBeast;
use WMHSim\Sim;

require './WMHSim/Sim.php';
require './WMHSim/Model.php';
require './WMHSim/Beast.php';
require './WMHSim/Factions/Example/ExampleBeast.php';

$laps 		= 5;
$success 	= 0;

$attacker	= new ExampleBeast();
$defender	= new ExampleBeast();

for ($i = 0; $i < $laps; $i++) {
	$sim = new Sim();
	$sim->setAttacker(clone $attacker);
	$sim->setDefender(clone $defender);
	
	$sim->run();
	
	echo "---\n";
}