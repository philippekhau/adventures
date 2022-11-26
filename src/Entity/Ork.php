<?php

namespace App\Entity;

use App\Repository\OrkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrkRepository::class)]
class Ork extends Monster
{
    public function __construct()
    {
        $this->setHp(10)
            ->setArmor(4);
    }

    public function getType(): string
    {
        return self::ORK_TYPE;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getAttack(): int
    {
        return random_int(1, 6);
    }
}
