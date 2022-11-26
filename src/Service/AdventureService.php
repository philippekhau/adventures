<?php

namespace App\Service;

use App\Entity\Adventure;
use Doctrine\Persistence\ManagerRegistry;

class AdventureService
{
    /**
     * @param ManagerRegistry $doctrine
     * @param TileService $tileService
     * @param CharacterService $characterService
     */
    public function __construct(
        private ManagerRegistry  $doctrine,
        private TileService      $tileService,
        private CharacterService $characterService
    )
    {
    }

    /**
     * @return Adventure
     * @throws \Exception
     */
    public function start(): Adventure
    {
        $entityManager = $this->doctrine->getManager();

        $adventure = new Adventure();
        $entityManager->persist($adventure);
        $entityManager->flush();

        $this->characterService->create($adventure);
        $tile = $this->tileService->create($adventure, true);
        $adventure->addTile($tile);

        return $adventure;
    }
}
