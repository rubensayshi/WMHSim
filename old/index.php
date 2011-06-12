<?
	@session_start();
?>
<style>
hr {
	clear: both;
	border: 1px solid black;
}

form {
	margin: 0px;
	padding: 0px;
}

div {
	border: 1px solid black;
	margin-right: 5px;
}
</style>

<?
function generateGraph($a_dataArray=array())
{
	$i_graphId = uniqid();
	$_SESSION['graphs'][$i_graphId] = $a_dataArray;

	echo '<img src="line.php?uniqId='.$i_graphId.'" />'; 
}

function debug($s_debugMsg)
{
	global $b_debug;
	if($b_debug)
	{
		echo $s_debugMsg;
	}
}

function rollDice($i_dices=2, $b_discardLowest=false)
{
	$a_diceRolls = array();
	for($i = 0; $i < $i_dices; $i++)
	{
		$a_diceRolls[] = rand(1,6);
	}
	
	if($b_discardLowes)
	{
		sort($a_diceRolls);
		array_shift($a_diceRolls);
	}	
	
	return array_sum($a_diceRolls);	
}

class warbeast
{
	var $s_warbeastName = 'unknown warbeast';
	
	var $i_str = 0;
	var $i_def = 0;
	var $i_arm = 0;
	var $i_mat = 0;
	var $i_dmg = 0;
	var $i_fury = 0;
	
	var $i_attackCount = 0;
	
	var $i_curFury = 0;
	var $a_weapons = array();
	var $a_buffs = array();
	
	var $b_boostHit = false;
	var $b_boostDmg = false;
	
	function warbeast($s_warbeastName, $a_stats=array(),$a_weapons=array())
	{
		$this->s_warbeastName = $s_warbeastName;
		
		$this->setStats($a_stats);
		
		$this->a_weapons = $a_weapons;	
	}
		
	function setStats($a_stats=array())
	{
		foreach($a_stats as $s_statName => $i_statVal)
		{
			$s_statVar = 'i_'.$s_statName;
			
			if(property_exists($this, $s_statVar))
			{
				$this->$s_statVar = $i_statVal;
			}
		}	
	}
		
	function infoDump()
	{
		$s_info = '<div style="float: left"><b>['.$this->s_warbeastName.']</b> <br />';
		$s_info .= 'FURY ['.$this->i_fury.'] MAT['.$this->i_mat.'] DEF ['.$this->i_def.'] ARM ['.$this->i_arm.'] <br />';
		foreach($this->a_weapons as $a_weaponInfo)
		{
			$s_info .= ' - '.$a_weaponInfo['name'] . ' P+S ['.($this->i_str + $a_weaponInfo['pow']).'] <br />';
		}	
		$s_info .= '</div>';
		
		return $s_info;
	}
		
	function attack($o_target)
	{
		global $b_stopOnDeath;
	
		debug('<hr /><u>['.$this->s_warbeastName.'] starts an attack on ['.$o_target->s_warbeastName.']</u><br />');
		$i_totalDmgDone = 0;
		
		foreach($this->a_weapons as $a_weaponInfo)
		{
			$i_totalDmgDone += $this->evalAttack($o_target, $a_weaponInfo);
			if($o_target->i_dmg <= 0)
			{
				debug('<b>['.$o_target->s_warbeastName.'] died</b>');
				if($b_stopOnDeath)
				{
					return ($i_totalDmgDone + $o_target->i_dmg);
				}
			}
		}
		
		while($this->i_curFury < $this->i_fury)
		{
			$this->i_curFury++;
			$i_totalDmgDone += $this->evalAttack($o_target, $this->a_weapons[0]);
			if($o_target->i_dmg <= 0)
			{
				debug('<b>['.$o_target->s_warbeastName.'] died</b>');
				if($b_stopOnDeath)
				{
					return ($i_totalDmgDone + $o_target->i_dmg);
				}
			}		
		}
		
		return $i_totalDmgDone;
	}
	
	function evalAttack($o_target, $a_weaponInfo)
	{	
		$this->i_attackCount++;
		debug('['.$this->s_warbeastName.'] attacks ['.$o_target->s_warbeastName.'] with '.$a_weaponInfo['name'].' <br />');
		$b_boostedHit = false;
		if($this->b_boostHit && $this->i_curFury < $this->i_fury)
		{
			$b_boostedHit = true;
			$this->i_curFury++;
		}
		
		if($this->hitRoll($o_target, $b_boostedHit))
		{		
			$b_boostedDmg = false;
			if($this->b_boostDmg && $this->i_curFury < $this->i_fury)
			{
				$b_boostedDmg = true;
				$this->i_curFury++;
			}
			$i_damageDone = $this->damageRoll($o_target, $a_weaponInfo['pow'], $b_boostedDmg);

			return $i_damageDone;
		}
	}
	
	function hitRoll($o_target, $b_boosted=false)
	{
		if($b_boosted)
		{
			$s_roll = '3D6';
			$i_roll = rollDice(3);		
		}
		else
		{
			$s_roll = '2D6';
			$i_roll = rollDice(2);
		}
				
		if($this->i_mat + $i_roll >= $o_target->i_def)
		{
			debug('['.$this->s_warbeastName.'] {MAT '.$this->i_mat.' + roll '.$i_roll.'('.$s_roll.') = '.($this->i_mat + $i_roll).' VS def '.$o_target->i_def.'} hit ['.$o_target->s_warbeastName.'] <br />');
			return true;
		}
		else
		{
			debug('['.$this->s_warbeastName.'] {MAT '.$this->i_mat.' + roll '.$i_roll.'('.$s_roll.') = '.($this->i_mat + $i_roll).' VS def '.$o_target->i_def.'} missed ['.$o_target->s_warbeastName.'] <br />');		
			return false;
		}
	}
	
	function damageRoll($o_target, $i_pow=0, $b_boosted=false)
	{
		if($b_boosted)
		{
			$s_roll = '3D6';
			$i_roll = rollDice(3);		
		}
		else
		{
			$s_roll = '2D6';
			$i_roll = rollDice(2);
		}
		
		$i_dmg = $this->i_str + $i_pow + $i_roll - $o_target->i_arm;
		if($i_dmg > 0)
		{
			$o_target->i_dmg -= $i_dmg;
			debug('['.$o_target->s_warbeastName.'] took ['.$i_dmg.'] {P+S '.($this->i_str + $i_pow).' + roll '.$i_roll.'('.$s_roll.') = '.($this->i_str + $i_pow + $i_roll).' - '.$o_target->i_arm.' = '.$i_dmg.'} damage ('.$o_target->i_dmg.' left) <br />');			
			return $i_dmg;
		}
		else
		{
			debug('['.$o_target->s_warbeastName.'] took no damage <br />');
			return 0;
		}
		
	}
}

function executeTest($o_attacker, $o_defender, $b_boostHit = false, $b_boostDmg = false)
{
	$o_attacker->b_boostHit = $b_boostHit;
	$o_attacker->b_boostDmg = $b_boostDmg;
	
	$i_damageDone = $o_attacker->attack($o_defender);
		
	if($o_defender->i_dmg <= 0)
	{
		return array($i_damageDone, 1);
	}
	else
	{
		return array($i_damageDone, 0);
	}
}

$b_stopOnDeath = false;
//$b_stopOnDeath = true;
$b_debug = false;
//$b_debug = true;
$i_laps = 500;
if($_GET['iterations'])
{
	$i_laps = $_GET['iterations'];
}

if($i_laps < 1) $i_laps = 1;
if($i_laps > 2000) $i_laps = 2000;


echo '<form action="" method="GET">Tests executed with ['.$i_laps.'] iterations. <input type="text" name="iterations" value="'.$i_laps.'"><input type="submit" value="change" /><br />
When there\'s fury left after (posibly) boosting we will buy extra attacks, applying our previous settings for boosting.</form>';

$o_sample1 = new warbeast('Sample Beastie #1', 
array(
	'str' => 8,
	'def' => 11,
	'arm' => 18,
	'mat' => 5,
	'fury' => 4,
	'dmg' => 30), 
array(
	array(
		'name' => 'head',
		'pow' => 4
	),
	array(
		'name' => 'claw',
		'pow' => 4
	),
	array(
		'name' => 'claw',
		'pow' => 4
	)
));

$o_sample2 = new warbeast('Sample Beastie #2', 
array(
	'str' => 12,
	'def' => 14,
	'arm' => 16,
	'mat' => 6,
	'fury' => 4,
	'dmg' => 30), 
array(
	array(
		'name' => 'head',
		'pow' => 4
	),
	array(
		'name' => 'claw',
		'pow' => 4
	),
	array(
		'name' => 'claw',
		'pow' => 4
	)
));

$a_testCases = array(
	array(
		'title' => 'PS10 vs ARM18',
		'xAxis' => -8,
		'o_attacker' => array(
			'str' => 6
		),
		'o_defender' => array(
			'arm' => 18,
			'def' => 0
		)
	),
	array(
		'title' => 'PS12 vs ARM18',
		'xAxis' => -6,
		'o_attacker' => array(
			'str' => 8
		),
		'o_defender' => array(
			'arm' => 18,
			'def' => 0
		)
	),
	array(
		'title' => 'PS14 vs ARM18',
		'xAxis' => -4,
		'o_attacker' => array(
			'str' => 10
		),
		'o_defender' => array(
			'arm' => 18,
			'def' => 0
		)
	),
	array(
		'title' => 'PS16 vs ARM18',
		'xAxis' => -2,
		'o_attacker' => array(
			'str' => 12
		),
		'o_defender' => array(
			'arm' => 18,
			'def' => 0
		)
	),
	array(
		'title' => 'PS18 vs ARM18',
		'xAxis' => 0,
		'o_attacker' => array(
			'str' => 14
		),
		'o_defender' => array(
			'arm' => 18,
			'def' => 0
		)
	),
	array(
		'title' => 'PS20 vs ARM18',
		'xAxis' => 2,
		'o_attacker' => array(
			'str' => 16
		),
		'o_defender' => array(
			'arm' => 18,
			'def' => 0
		)
	),
	array(
		'title' => 'PS22 vs ARM18',
		'xAxis' => 4,
		'o_attacker' => array(
			'str' => 18
		),
		'o_defender' => array(
			'arm' => 18,
			'def' => 0
		)
	)
);

$a_testResults = array();
foreach($a_testCases as $a_caseData)
{	
	$a_caseResult = array();
	$a_caseResult['title'] = '';
	$a_caseResult['results'] = array();
	$o_attacker = $o_sample1;
	$o_attacker->setStats($a_caseData['o_attacker']);
	
	$o_defender = $o_sample2;
	$o_defender->setStats($a_caseData['o_defender']);
		
	if($a_caseData['title'])
	{
		$a_caseResult['title'] = $a_caseData['title'];	
	}
	else
	{
		$a_caseResult['title'] = '['.$o_attacker->s_warbeastName.'] attacking ['.$o_defender->s_warbeastName.']';
	}
	$a_caseResult['info'] = $o_attacker->infoDump() . $o_defender->infoDump();
	
	/*
		No boosted rolls, just exta attacks
	*/
	$i_total = 0;
	$i_deaths = 0;
	for($i = 0; $i < $i_laps; $i++)
	{
		$a_result = executeTest(clone $o_attacker,clone $o_defender);
		$i_total += $a_result[0];
		$i_deaths += $a_result[1];
	}

	$i_avarageDmg = round(($i_total / $i_laps),2);
	$i_chanceToKill = round(($i_deaths / $i_laps)*100,2);
	$i_percentageHp = round(($i_avarageDmg / $o_defender->i_dmg)*100,2);
	$a_caseResult['results'][] = array(
		'title' => 'Plain',
		'avarageDmg' => $i_avarageDmg,
		'chanceToKill' => $i_chanceToKill,
		'percentage' => $i_percentageHp
	);
	
	/*
		Boosted TO HIT
	*/
	$i_total = 0;
	$i_deaths = 0;
	for($i = 0; $i < $i_laps; $i++)
	{
		$a_result = executeTest(clone $o_attacker,clone $o_defender, true);
		$i_total += $a_result[0];
		$i_deaths += $a_result[1];
	}

	$i_avarageDmg = round(($i_total / $i_laps),2);
	$i_chanceToKill = round(($i_deaths / $i_laps)*100,2);
	$i_percentageHp = round(($i_avarageDmg / $o_defender->i_dmg)*100,2);
	$a_caseResult['results'][] = array(
		'title' => 'Boosted attack',
		'avarageDmg' => $i_avarageDmg,
		'chanceToKill' => $i_chanceToKill,
		'percentage' => $i_percentageHp
	);
	/*
		Boosted DMG
	*/
	$i_total = 0;
	$i_deaths = 0;
	for($i = 0; $i < $i_laps; $i++)
	{
		$a_result = executeTest(clone $o_attacker,clone $o_defender, false, true);
		$i_total += $a_result[0];
		$i_deaths += $a_result[1];
	}

	$i_avarageDmg = round(($i_total / $i_laps),2);
	$i_chanceToKill = round(($i_deaths / $i_laps)*100,2);
	$i_percentageHp = round(($i_avarageDmg / $o_defender->i_dmg)*100,2);
	$a_caseResult['results'][] = array(
		'title' => 'Boosted damage',
		'avarageDmg' => $i_avarageDmg,
		'chanceToKill' => $i_chanceToKill,
		'percentage' => $i_percentageHp
	);
	
	/*
		Boosted BOTH
	*/
	$i_total = 0;
	$i_deaths = 0;
	for($i = 0; $i < $i_laps; $i++)
	{
		$a_result = executeTest(clone $o_attacker,clone $o_defender, false, true);
		$i_total += $a_result[0];
		$i_deaths += $a_result[1];
	}

	$i_avarageDmg = round(($i_total / $i_laps),2);
	$i_chanceToKill = round(($i_deaths / $i_laps)*100,2);
	$i_percentageHp = round(($i_avarageDmg / $o_defender->i_dmg)*100,2);
	$a_caseResult['results'][] = array(
		'title' => 'Boosted attack & dmg',
		'avarageDmg' => $i_avarageDmg,
		'chanceToKill' => $i_chanceToKill,
		'percentage' => $i_percentageHp
	);
	
	$a_testResults[$a_caseData['xAxis']] = $a_caseResult;
}

$a_graphData = array();

foreach($a_testResults as $k2 => $a_caseResult)
{
	echo '<hr />';
	echo '<b>'.$a_caseResult['title'].'</b><br /><br />';
	echo ''.$a_caseResult['info'].'<br />';
	echo '<br style="clear: both;" />';
	foreach($a_caseResult['results'] as $k => $a_testInfo)
	{
		$a_graphData[$k][$k2] = $a_testInfo['avarageDmg'];
		$a_graphData[$k]['title'] = $a_testInfo['title'];
	
		echo '<b>'.$a_testInfo['title'].'</b><br />';
		echo $a_testInfo['avarageDmg'].' damage done ('.$a_testInfo['percentage'].'% of total hp) '.$a_testInfo['chanceToKill'].'% chance to kill.<br />';
	}
}

echo '<hr /><span style="color: green;">All tests completed</span><hr />';
generateGraph($a_graphData);
?>   