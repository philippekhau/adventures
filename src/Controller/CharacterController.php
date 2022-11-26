<?php

namespace App\Controller;

use App\Entity\Character;
use App\Exception\ActionNotAllowedException;
use App\Exception\AdventureOverException;
use App\Exception\CharacterDeadException;
use App\Serializer\Normalizer\CharacterNormalizer;
use App\Service\CharacterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    /**
     * @param CharacterNormalizer $characterNormalizer
     * @param CharacterService $characterService
     */
    public function __construct(
        private CharacterNormalizer $characterNormalizer,
        private CharacterService    $characterService
    )
    {
    }

    #[Route('/character/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Character $character)
    {
        return $this->json([
            'data' => $this->characterNormalizer->normalize($character)
        ]);
    }

    #[Route('/character/{id}/action/move', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function actionMove(Character $character): JsonResponse
    {
        return $this->action('move', $character);
    }

    #[Route('/character/{id}/action/attack', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function actionAttack(Character $character): JsonResponse
    {
        return $this->action('attack', $character);
    }

    #[Route('/character/{id}/action/rest', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function actionRest(Character $character): JsonResponse
    {
        return $this->action('rest', $character);
    }

    /**
     * @param string $type
     * @param Character $character
     * @return JsonResponse
     */
    protected function action(string $type, Character $character): JsonResponse
    {
        try {
            $this->characterService->$type($character);
        } catch (ActionNotAllowedException $exception) {
            return $this->json([
                'data' => 'Action not allowed.'
            ], 403);
        } catch (CharacterDeadException $exception) {
            return $this->json([
                'data' => 'Character is dead.'
            ]);
        } catch (AdventureOverException $exception) {
            return $this->json([
                'data' => 'Adventure is over.'
            ]);
        }

        return $this->json([
            'data' => $this->characterNormalizer->normalize($character)
        ]);
    }
}
