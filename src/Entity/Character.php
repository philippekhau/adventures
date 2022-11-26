<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $hp = null;

    #[ORM\Column]
    private ?int $armor = null;

    #[ORM\OneToOne(inversedBy: 'character', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adventure $adventure = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $resting = null;

    public function __construct()
    {
        $this->setHp(20)
            ->setArmor(5)
            ->setResting(false);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getAttack(): int
    {
        return random_int(1, 6) + random_int(1, 6);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getArmor(): ?int
    {
        return $this->armor;
    }

    public function setArmor(int $armor): self
    {
        $this->armor = $armor;

        return $this;
    }

    public function getAdventure(): ?Adventure
    {
        return $this->adventure;
    }

    public function setAdventure(Adventure $adventure): self
    {
        // set the owning side of the relation if necessary
        if ($adventure->getCharacter() !== $this) {
            $adventure->setCharacter($this);
        }

        $this->adventure = $adventure;

        return $this;
    }

    public function isResting(): ?bool
    {
        return $this->resting;
    }

    public function setResting(bool $resting): self
    {
        $this->resting = $resting;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->getHp() > 0;
    }
}
