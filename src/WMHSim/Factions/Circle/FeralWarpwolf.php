<?php

namespace WMHSim\Factions\Circle;

use WMHSim\Beast;

class FeralWarpwolf extends Beast
{
    protected $name = 'FeralWarpwolf';

    protected $str = 11;
    protected $def = 14;
    protected $arm = 16;
    protected $mat = 7;
    protected $dmg = 28;
    protected $fury = 4;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name' => 'Claw',
            'pow' => 4
        ),
        array(
            'name' => 'Claw',
            'pow' => 4
        ),
        array(
            'name' => 'Bite',
            'pow' => 3
        ),
    );
}