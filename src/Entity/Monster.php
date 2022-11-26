<?php

namespace App\Entity;

use App\Entity\Contract\TypeInterface;
use App\Repository\MonsterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonsterRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([self::ORK_TYPE => Ork::class, self::GOBELIN_TYPE => Gobelin::class, self::GHOST_TYPE => Ghost::class, self::TROLL_TYPE => Troll::class, self::DRAGON_TYPE => Dragon::class])]
abstract class Monster implements TypeInterface
{
    public const ORK_TYPE = 'ork';
    public const GOBELIN_TYPE = 'gobelin';
    public const GHOST_TYPE = 'ghost';
    public const TROLL_TYPE = 'troll';
    public const DRAGON_TYPE = 'dragon';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $armor = null;

    #[ORM\Column]
    private ?int $hp = null;

    #[ORM\OneToOne(inversedBy: 'monster', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tile $tile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttack(): int
    {
        return 0;
    }

    public function getArmor(): ?int
    {
        return $this->armor;
    }

    public function setArmor(int $armor): self
    {
        $this->armor = $armor;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(int $hp): self
    {
        $this->hp = $hp;

        return $this;
    }

    public function getTile(): ?Tile
    {
        return $this->tile;
    }

    public function setTile(Tile $tile): self
    {
        $this->tile = $tile;

        return $this;
    }

    public function isAlive(): bool
    {
        return $this->getHp() > 0;
    }
}
