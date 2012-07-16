<?php

namespace WMHSim\Factions\Legion;

use WMHSim\Beast;

class Proteus extends Beast
{
    protected $name = 'Proteus';

    protected $str = 12;
    protected $def = 11;
    protected $arm = 18;
    protected $mat = 6;
    protected $dmg = 30;
    protected $fury = 5;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'    => 'Talon',
            'pow'    => 16,
        ),
        array(
            'name'    => 'Talon',
            'pow'    => 16,
        ),
        array(
            'name'    => 'Tentacles',
            'pow'    => 14,
        ),
    );
}