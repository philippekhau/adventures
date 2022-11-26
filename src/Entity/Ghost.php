<?php

namespace App\Entity;

use App\Repository\GhostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GhostRepository::class)]
class Ghost extends Monster
{
    public function __construct()
    {
        $this->setHp(8)
            ->setArmor(6);
    }

    public function getType(): string
    {
        return self::GHOST_TYPE;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getAttack(): int
    {
        return random_int(1, 4);
    }
}
