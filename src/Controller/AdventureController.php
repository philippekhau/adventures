<?php

namespace App\Controller;

use App\Repository\AdventureRepository;
use App\Serializer\Normalizer\AdventureNormalizer;
use App\Serializer\Normalizer\TileNormalizer;
use App\Service\AdventureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdventureController extends AbstractController
{
    /**
     * @param AdventureNormalizer $adventureNormalizer
     * @param AdventureRepository $adventureRepository
     */
    public function __construct(
        private AdventureNormalizer $adventureNormalizer,
        private AdventureRepository $adventureRepository
    )
    {
    }

    /**
     * @throws \Exception
     */
    #[Route('/adventure/start', methods: ['POST'])]
    public function start(AdventureService $service): JsonResponse
    {
        $adventure = $service->start();

        return $this->json([
            'data' => $this->adventureNormalizer->normalize($adventure, null, ['character', 'tile.monster'])
        ]);
    }

    #[Route('/adventure/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id)
    {
        $relations = ['character', 'tile.monster', 'logs'];

        $adventure = $this->adventureRepository->eagerFind($id, $relations);

        if (!$adventure) {
            throw $this->createNotFoundException();
        }

        return $this->json([
            'data' => $this->adventureNormalizer->normalize($adventure, null, $relations)
        ]);
    }

    #[Route('/adventure/{id}/tile', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showTile(int $id, TileNormalizer $tileNormalizer)
    {
        $adventure = $this->adventureRepository->eagerFind($id, ['tile.monster']);

        if (!$adventure) {
            throw $this->createNotFoundException();
        }

        return $this->json([
            'data' => $tileNormalizer->normalize($adventure->getTile()->first(), null, ['monster'])
        ]);
    }
}
