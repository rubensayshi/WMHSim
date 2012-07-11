<?php

namespace WMHSim\Factions\Legion;

use WMHSim\Warlock;

class Rhyas extends Warlock
{
    protected $name = 'Rhyas';

    protected $str = 8;
    protected $def = 15;
    protected $arm = 14;
    protected $mat = 8;
    protected $dmg = 18;
    protected $fury = 5;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'   => 'Sword',
            'pow'    => 4,
            'weapon-master' => true,
            'crit-decap'    => true
        ),
    );
}