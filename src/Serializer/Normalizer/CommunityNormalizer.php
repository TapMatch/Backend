<?php

namespace App\Serializer\Normalizer;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CommunityNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
            'city' => $object->getCity(),
            'is_open' => $object->getIsOpen(),
            'events' => $object->getEvents(),
            'members' => array_map(function (User $user) {
                return [
                    'id' => $user->getId(),
                    'name' => $user->getFirstName(),
                    'phone' => $user->getPhone(),
                ];
            },
                $object->getUsers()->toArray())
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \App\Entity\Community;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
