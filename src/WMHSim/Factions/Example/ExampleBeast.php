<?php 

namespace WMHSim\Factions\Example;

use WMHSim\Beast;

class ExampleBeast extends Beast
{
    protected $name = 'ExampleBeast';
    
    protected $str = 12;
    protected $def = 11;
    protected $arm = 18;
    protected $mat = 6;
    protected $dmg = 30;
    protected $fury = 4;
    
    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'    => 'Head',
            'pow'    => 6,
        ),
        array(
            'name'    => 'Claw',
            'pow'    => 4,
        ),
        array(
            'name'    => 'Claw',
            'pow'    => 4,
        ),
    );
}