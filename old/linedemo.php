<?php
@session_start();

function generateGraph($a_dataArray=array())
{
	$i_graphId = uniqid();
	$_SESSION['graphs'][$i_graphId] = $a_dataArray;

	echo '<img src="line.php?uniqId='.$i_graphId.'" />'; 
}

generateGraph(array
(
	array(	// pink
		'title' => 'pinky',
		-1 => 10,
		1 => 20,
		2 => 30,
		3 => 40,
		4 => 50,
		5 => 60
	),
	array(	// blue
		'title' => 'blauwtje',
		0 => 10,
		1 => 33,
		2 => 47,
		3 => 21,
		4 => 3,
		5 => 77)
));
?> 