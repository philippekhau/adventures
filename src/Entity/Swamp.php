<?php

namespace App\Entity;

use App\Repository\SwampRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SwampRepository::class)]
class Swamp extends Tile
{
    public function allowMovement(Monster|Character $entity): bool
    {
        return parent::allowMovement($entity) && $entity instanceof Character && $this->getMonster()->getHp() === 0;
    }

    public function getType(): string
    {
        return self::SWAMP_TYPE;
    }
}
