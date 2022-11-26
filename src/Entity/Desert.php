<?php

namespace App\Entity;

use App\Repository\DesertRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DesertRepository::class)]
class Desert extends Tile
{
    public function hpMalus(Monster|Character $entity): int
    {
        return $entity instanceof Character && $this->getMonster()->getType() !== Monster::DRAGON_TYPE ? 1 : 0;
    }

    public function getType(): string
    {
        return self::DESERT_TYPE;
    }
}
