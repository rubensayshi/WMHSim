<?php

namespace WMHSim\Factions\Example;

use WMHSim\Warlock;

class ExampleWarlock extends Warlock
{
    protected $name = 'ExampleWarlock';

    protected $str = 7;
    protected $def = 16;
    protected $arm = 15;
    protected $mat = 8;
    protected $dmg = 17;
    protected $fury = 7;

    protected $tough = true;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'   => 'Pointy Stick',
            'pow'    => 5,
        ),
    );
}