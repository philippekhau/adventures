<?php

namespace App\Entity;

use App\Repository\HillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HillRepository::class)]
class Hill extends Tile
{
    public function attackBonus(Monster|Character $entity): int
    {
        return $entity instanceof Ghost ? 2 : 0;
    }

    public function getType(): string
    {
        return self::HILL_TYPE;
    }
}
