<?php

namespace App\Entity;

use App\Repository\GobelinRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GobelinRepository::class)]
class Gobelin extends Monster
{
    public function __construct()
    {
        $this->setHp(15)
            ->setArmor(0);
    }

    public function getType(): string
    {
        return self::GOBELIN_TYPE;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getAttack(): int
    {
        return random_int(1, 4) - 1;
    }
}
