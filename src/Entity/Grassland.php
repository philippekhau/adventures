<?php

namespace App\Entity;

use App\Repository\GrasslandRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrasslandRepository::class)]
class Grassland extends Tile
{
    public function attackBonus(Monster|Character $entity): int
    {
        return $entity instanceof Ork ? 2 : 0;
    }

    public function getType(): string
    {
        return self::GRASSLAND_TYPE;
    }
}
