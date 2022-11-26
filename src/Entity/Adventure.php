<?php

namespace App\Entity;

use App\Repository\AdventureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdventureRepository::class)]
class Adventure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(options: ["default" => 0])]
    private int $score;

    #[ORM\OneToMany(mappedBy: 'adventure', targetEntity: Tile::class)]
    private Collection $tile;

    #[ORM\OneToOne(mappedBy: 'adventure', cascade: ['persist', 'remove'], fetch: "EXTRA_LAZY")]
    private ?Character $character = null;

    #[ORM\OneToMany(mappedBy: 'adventure', targetEntity: Log::class, orphanRemoval: true)]
    private Collection $logs;

    public function __construct()
    {
        $this->score = 0;
        $this->tile = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection<int, Tile>
     */
    public function getTile(): Collection
    {
        return $this->tile;
    }

    public function addTile(Tile $tile): self
    {
        if (!$this->tile->contains($tile)) {
            $this->tile->add($tile);
            $tile->setAdventure($this);
        }

        return $this;
    }

    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    public function setCharacter(Character $character): self
    {
        $this->character = $character;

        return $this;
    }

    /**
     * @return Collection<int, Log>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Log $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setAdventure($this);
        }

        return $this;
    }

    public function removeLog(Log $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getAdventure() === $this) {
                $log->setAdventure(null);
            }
        }

        return $this;
    }
}
