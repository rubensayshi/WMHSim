<?php 

namespace WMHSim\Factions\Example;

use WMHSim\Beast;

class ExampleBeast extends Beast
{
	protected $name = 'ExampleBeast';
	
	protected $str = 12;
	protected $def = 14;
	protected $arm = 18;
	protected $mat = 7;
	protected $dmg = 25;
	protected $fury = 4;
	
	protected $curFury = 0;
	protected $weapons = array(
		array(
			'name'	=> 'Head',
			'pow'	=> 4,
		),
		array(
			'name'	=> 'Head',
			'pow'	=> 4,
		),
	);
}