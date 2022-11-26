<?php

namespace App\Entity;

use App\Repository\MountainRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MountainRepository::class)]
class Mountain extends Tile
{
    public function attackBonus(Monster|Character $entity): int
    {
        return $entity instanceof Troll ? 2 : 0;
    }

    public function getType(): string
    {
        return self::MOUNTAIN_TYPE;
    }
}
