<?php

namespace App\Entity;

use App\Repository\DragonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DragonRepository::class)]
class Dragon extends Monster
{
    public function __construct()
    {
        $this->setHp(20)
            ->setArmor(8);
    }

    public function getType(): string
    {
        return self::DRAGON_TYPE;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getAttack(): int
    {
        return random_int(1, 6) + 2;
    }
}
