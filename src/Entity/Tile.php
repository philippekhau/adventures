<?php

namespace App\Entity;

use App\Entity\Contract\BoostsAttack;
use App\Entity\Contract\ReducesHp;
use App\Entity\Contract\RestrictsMovement;
use App\Entity\Contract\TypeInterface;
use App\Repository\TileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TileRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([self::GRASSLAND_TYPE => Grassland::class, self::HILL_TYPE => Hill::class, self::FOREST_TYPE => Forest::class, self::MOUNTAIN_TYPE => Mountain::class, self::DESERT_TYPE => Desert::class, self::SWAMP_TYPE => Swamp::class])]
abstract class Tile implements BoostsAttack, ReducesHp, RestrictsMovement, TypeInterface
{
    public const GRASSLAND_TYPE = 'grassland';
    public const HILL_TYPE = 'hill';
    public const FOREST_TYPE = 'forest';
    public const MOUNTAIN_TYPE = 'mountain';
    public const DESERT_TYPE = 'desert';
    public const SWAMP_TYPE = 'swamp';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'tile', cascade: ['persist', 'remove'])]
    private ?Monster $monster = null;

    #[ORM\ManyToOne(inversedBy: 'tile')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adventure $adventure = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $active;

    public function __construct()
    {
        $this->active = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    public function setMonster(Monster $monster): self
    {
        // set the owning side of the relation if necessary
        if ($monster->getTile() !== $this) {
            $monster->setTile($this);
        }

        $this->monster = $monster;

        return $this;
    }

    public function getAdventure(): ?Adventure
    {
        return $this->adventure;
    }

    public function setAdventure(Adventure $adventure): self
    {
        $this->adventure = $adventure;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function attackBonus(Monster|Character $entity): int
    {
        return 0;
    }

    public function hpMalus(Monster|Character $entity): int
    {
        return 0;
    }

    public function allowMovement(Monster|Character $entity): bool
    {
        return $this->getMonster()->getType() !== Monster::DRAGON_TYPE;
    }
}
