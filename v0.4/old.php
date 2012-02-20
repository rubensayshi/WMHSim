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
	
	var $i_curFury = 0;
	var $a_weapons = array();
	var $a_buffs = array();
	
	var $b_boostHit = false;
	var $b_boostDmg = false;
	
	function warbeast($s_warbeastName, $a_stats=array(),$a_weapons=array())
	{
		$this->s_warbeastName = $s_warbeastName;
		
		foreach($a_stats as $s_statName => $i_statVal)
		{
			$s_statVar = 'i_'.$s_statName;
			
			if(property_exists($this, $s_statVar))
			{
				$this->$s_statVar = $i_statVal;
			}
		}
		
		$this->a_weapons = $a_weapons;	
		
		$this->infoDump();
	}
		
	function infoDump()
	{
		echo '<div style="float: left"><b>['.$this->s_warbeastName.']</b> <br />';
		echo 'FURY ['.$this->i_fury.'] MAT['.$this->i_mat.'] DEF ['.$this->i_def.'] ARM ['.$this->i_arm.'] <br />';
		foreach($this->a_weapons as $a_weaponInfo)
		{
			echo ' - '.$a_weaponInfo['name'] . ' P+S ['.($this->i_str + $a_weaponInfo['pow']).'] <br />';
		}	
		echo '</div>';
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

$o_carni = new warbeast('Carnivean', 
array(
	'str' => 12,
	'def' => 11,
	'arm' => 18,
	'mat' => 6,
	'fury' => 4,
	'dmg' => 30), 
array(
	array(
		'name' => 'head',
		'pow' => 6
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

$o_carni2 = new warbeast('Carnivean +SpinyGrowth', 
array(
	'str' => 12,
	'def' => 11,
	'arm' => 20,
	'mat' => 6,
	'fury' => 4,
	'dmg' => 30), 
array(
	array(
		'name' => 'head',
		'pow' => 6
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

$o_carni3 = new warbeast('Carnivean +Incite+Chiller', 
array(
	'str' => 14,
	'def' => 11,
	'arm' => 18,
	'mat' => 10,
	'fury' => 4,
	'dmg' => 30), 
array(
	array(
		'name' => 'head',
		'pow' => 6
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

$o_warpwolf = new warbeast('Feral Warpwolf +warpStr', 
array(
	'str' => 13, // with warping
	'def' => 14,
	'arm' => 16, // withouth warping
	'mat' => 7,
	'fury' => 4,
	'dmg' => 28), 
array(
	array(
		'name' => 'claw',
		'pow' => 4
	),
	array(
		'name' => 'claw',
		'pow' => 4
	),
	array(
		'name' => 'bite',
		'pow' => 3
	)
));
$o_warpwolf2 = new warbeast('Feral Warpwolf +warpArm', 
array(
	'str' => 11, // withouth warping
	'def' => 14,
	'arm' => 18, // with warping
	'mat' => 7,
	'fury' => 4,
	'dmg' => 28), 
array(
	array(
		'name' => 'claw',
		'pow' => 4
	),
	array(
		'name' => 'claw',
		'pow' => 4
	),
	array(
		'name' => 'bite',
		'pow' => 3
	)
));

$a_testCases = array(
	array(
		'o_attacker' => $o_carni,
		'o_defender' => $o_warpwolf
	),
	array(
		'o_attacker' => $o_carni,
		'o_defender' => $o_warpwolf2
	),
	array(
		'o_attacker' => $o_carni3,
		'o_defender' => $o_warpwolf
	),
	array(
		'o_attacker' => $o_warpwolf2,
		'o_defender' => $o_carni2
	),
	array(
		'o_attacker' => $o_warpwolf,
		'o_defender' => $o_carni2
	),
	array(
		'o_attacker' => $o_warpwolf,
		'o_defender' => $o_carni
	)
);

foreach($a_testCases as $a_caseData)
{	
	$o_attacker = $a_caseData['o_attacker'];
	$o_defender = $a_caseData['o_defender'];
	
	echo '<hr /><b>['.$o_attacker->s_warbeastName.'] attacking ['.$o_defender->s_warbeastName.']</b><br />';
	
	/*
		No boosted rolls, just exta attacks
	*/
	echo '<br /><b>Plain</b><br />';
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
	echo $i_avarageDmg.' damage done ('.$i_percentageHp.'% of total hp) '.$i_chanceToKill.'% chance to kill';
	
	/*
		Boosted TO HIT
	*/
	echo '<br /><b>Boosted attack</b><br />';
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
	echo $i_avarageDmg.' damage done ('.$i_percentageHp.'% of total hp) '.$i_chanceToKill.'% chance to kill';

	/*
		Boosted DMG
	*/
	echo '<br /><b>Boosted damage</b><br />';
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
	echo $i_avarageDmg.' damage done ('.$i_percentageHp.'% of total hp) '.$i_chanceToKill.'% chance to kill';
	
	/*
		Boosted BOTH
	*/
	echo '<br /><b>Boosted attack & dmg</b><br />';
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
	echo $i_avarageDmg.' damage done ('.$i_percentageHp.'% of total hp) '.$i_chanceToKill.'% chance to kill';

}

echo '<hr /><span style="color: green;">All tests completed</span>';
?>   