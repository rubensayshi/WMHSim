<?php

namespace WMHSim\Factions\Legion;

use WMHSim\Beast;

class Carnivean extends Beast
{
    protected $name = 'Carnivean';

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