<?php

namespace App\Entity\Contract;

use App\Entity\Character;
use App\Entity\Monster;

interface RestrictsMovement
{
    public function allowMovement(Monster|Character $entity): bool;
}
