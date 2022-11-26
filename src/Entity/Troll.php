<?php

namespace App\Entity;

use App\Repository\TrollRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrollRepository::class)]
class Troll extends Monster
{
    public function __construct()
    {
        $this->setHp(12)
            ->setArmor(6);
    }

    public function getType(): string
    {
        return self::TROLL_TYPE;
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
