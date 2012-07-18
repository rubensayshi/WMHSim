<?php

namespace WMHSim\Factions\Cryx;

use WMHSim\Warlock;

class Terminus extends Warlock
{
    protected $name = 'Terminus';

    protected $str = 8;
    protected $def = 12;
    protected $arm = 20;
    protected $mat = 8;
    protected $dmg = 20;
    protected $fury = 5;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'   => 'Sword',
            'pow'    => 4,
        ),
    );
}