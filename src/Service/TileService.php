<?php

namespace App\Service;

use App\Entity\Adventure;
use App\Entity\Desert;
use App\Entity\Forest;
use App\Entity\Grassland;
use App\Entity\Hill;
use App\Entity\Mountain;
use App\Entity\Swamp;
use App\Entity\Tile;
use Doctrine\Persistence\ManagerRegistry;

class TileService
{
    /**
     * @param ManagerRegistry $doctrine
     * @param MonsterService $monsterService
     */
    public function __construct(
        private ManagerRegistry $doctrine,
        private MonsterService  $monsterService
    )
    {
    }

    /**
     * @param Adventure $adventure
     * @param bool $active
     * @param bool $boss
     * @return Tile
     * @throws \Exception
     */
    public function make(Adventure $adventure, bool $active = false, bool $boss = false): Tile
    {
        $types = [Grassland::class, Hill::class, Forest::class, Mountain::class, Desert::class, Swamp::class];
        $className = random_int(0, count($types) - 1);

        $monster = $this->monsterService->make($boss);

        /** @var Tile $tile */
        $tile = new $types[$className];
        $tile
            ->setAdventure($adventure)
            ->setMonster($monster)
            ->setActive($active);

        return $tile;
    }

    /**
     * @param Adventure $adventure
     * @param bool $active
     * @param bool $boss
     * @return Tile
     * @throws \Exception
     */
    public function create(Adventure $adventure, bool $active = false, bool $boss = false): Tile
    {
        $tile = $this->make($adventure, $active, $boss);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tile);
        $entityManager->flush();

        return $tile;
    }
}
