<?php

namespace App\Entity\Contract;

use App\Entity\Character;
use App\Entity\Monster;

interface BoostsAttack
{
    public function attackBonus(Monster|Character $entity): int;
}
