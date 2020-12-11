<?php

namespace App\Serializer\Normalizer;

use App\Entity\Community;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EventNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'datetime' => $object->getDate(),
            'address' => $object->getAddress(),
            'coordinates' => $object->getCoordinates(),
            'description' => $object->getDescription(),
            'join_limit' => $object->getJoinLimit(),
            'joined' => count($object->getMembers()),
            'organizer' => $object->getOrganizer()->getId()
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \App\Entity\Event;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
