<?php

namespace App\Serializer\Normalizer;

use App\Entity\Adventure;
use App\Entity\Monster;
use App\Entity\Tile;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdventureNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param TileNormalizer $tileNormalizer
     * @param CharacterNormalizer $characterNormalizer
     * @param LogNormalizer $logNormalizer
     */
    public function __construct(
        private TileNormalizer      $tileNormalizer,
        private CharacterNormalizer $characterNormalizer,
        private LogNormalizer       $logNormalizer
    )
    {
    }

    /**
     * @param Adventure $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [
            'id' => $object->getId(),
            'score' => $object->getScore(),
        ];

        foreach ($context as $c) {
            switch ($c) {
                case 'character':
                    $data['character'] = $this->characterNormalizer->normalize($object->getCharacter());
                    break;
                case 'tile.monster':
                case 'tile':
                    /** @var Tile $tile */
                    $tile = $object->getTile()->first();

                    if ($c === 'tile.monster') {
                        $data['tile'] = $this->tileNormalizer->normalize($tile, null, ['monster']);
                    } else {
                        $data['tile'] = $this->tileNormalizer->normalize($tile);
                    }
                    break;
                case 'logs':
                    $logs = $object->getLogs();
                    $data['logs'] = [];

                    foreach ($logs as $log) {
                        $data['logs'][] = $this->logNormalizer->normalize($log);
                    }
            }
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Adventure;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
