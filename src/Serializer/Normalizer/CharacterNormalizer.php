<?php

namespace App\Serializer\Normalizer;

use App\Entity\Character;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CharacterNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param Character $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'hp' => $object->getHp(),
            'armor' => $object->getArmor()
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Character;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
