<?php

namespace App\Entity;

use App\Repository\ForestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForestRepository::class)]
class Forest extends Tile
{
    public function attackBonus(Monster|Character $entity): int
    {
        return $entity instanceof Gobelin ? 2 : 0;
    }

    public function getType(): string
    {
        return self::FOREST_TYPE;
    }
}
