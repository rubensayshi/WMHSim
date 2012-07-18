<?php

namespace WMHSim\Factions\Example;

use WMHSim\Warlock;

class ExampleWarlock extends Warlock
{
    protected $name = 'ExampleWarlock';

    protected $str = 7;
    protected $def = 15;
    protected $arm = 15;
    protected $mat = 8;
    protected $dmg = 18;
    protected $fury = 7;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'   => 'Pointy Stick',
            'pow'    => 5,
        ),
    );
}