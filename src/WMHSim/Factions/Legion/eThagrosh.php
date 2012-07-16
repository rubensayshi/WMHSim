<?php

namespace WMHSim\Factions\Legion;

use WMHSim\Warlock;

class eThagrosh extends Warlock
{
    protected $name = 'eThagrosh';

    protected $str = 11;
    protected $def = 13;
    protected $arm = 17;
    protected $mat = 7;
    protected $dmg = 20;
    protected $fury = 7;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'   => 'Rapture',
            'pow'    => 7,
        ),
        array(
            'name'   => 'Claw',
            'pow'    => 3,
        ),
    );
}