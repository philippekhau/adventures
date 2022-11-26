<?php

namespace App\Serializer\Normalizer;

use App\Entity\Monster;
use App\Entity\Tile;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TileNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param Tile $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [
            'id' => $object->getId(),
            'type' => $object->getType(),
        ];

        if (in_array('monster', $context)) {
            /** @var Monster $monster */
            $monster = $object->getMonster();

            $data['monster'] = [
                'id' => $monster->getId(),
                'type' => $monster->getType(),
                'hp' => $monster->getHp(),
                'armor' => $monster->getArmor()
            ];
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Tile;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
