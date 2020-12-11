<?php

namespace App\Serializer\Normalizer;

use App\Entity\Community;
use App\Entity\Event;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
            'name' => $object->getFirstName(),
            'avatar' => $object->getAvatar(),
            'phone' => $object->getPhone(),
            'finished_onboarding' => $object->getFinishedOnboarding(),
            'events' => array_map(function (Event $event) {
                return [
                    'id' => $event->getId(),
                    'name' => $event->getName(),
                    'date' => $event->getDate(),
                    'address' => $event->getAddress(),
                    'coordinates' => $event->getCoordinates(),
                    'description' => $event->getDescription(),
                    'join_limit' => $event->getJoinLimit()
                ];
            },
                $object->getEvents()->toArray())

            ,
            'communities' => array_map(function (Community $community) {
                return [
                    'id' => $community->getId(),
                    'name' => $community->getName(),
                    'access' => $community->getAccess(),
                ];
            },
                $object->getCommunities()->toArray())
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \App\Entity\User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
