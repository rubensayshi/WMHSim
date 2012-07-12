<?php

namespace WMHSim\Factions\Legion;

use WMHSim\Warlock;

class Rhyas extends Warlock
{
    protected $name = 'Rhyas';

    protected $str = 5;
    protected $def = 16;
    protected $arm = 14;
    protected $mat = 8;
    protected $dmg = 16;
    protected $fury = 5;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'   => 'Antiphon',
            'pow'    => 7,
            'weapon-master' => true,
            'crit-decap'    => true
        ),
    );
}