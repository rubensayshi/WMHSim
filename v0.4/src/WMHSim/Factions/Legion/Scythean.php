<?php

namespace WMHSim\Factions\Legion;

use WMHSim\Beast;

class Scythean extends Beast
{
    protected $name = 'Scythean';

    protected $str = 12;
    protected $def = 11;
    protected $arm = 18;
    protected $mat = 6;
    protected $dmg = 30;
    protected $fury = 4;

    protected $curFury = 0;
    protected $weapons = array(
        array(
            'name'    => 'Scythe',
            'pow'    => 5,
        ),
        array(
            'name'    => 'Scythe',
            'pow'    => 5,
        ),
    );

    protected function chainAttack($defender) {
        return $this->doAttack($defender, reset($this->weapons));
    }
}