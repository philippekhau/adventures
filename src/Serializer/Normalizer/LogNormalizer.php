<?php

namespace App\Serializer\Normalizer;

use App\Entity\Log;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LogNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param Log $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'message' => $object->getMessage(),
            'created_at' => $object->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Log;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
