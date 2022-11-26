<?php

namespace App\Entity\Contract;

use App\Entity\Character;
use App\Entity\Monster;

interface ReducesHp
{
    public function hpMalus(Monster|Character $entity): int;
}
