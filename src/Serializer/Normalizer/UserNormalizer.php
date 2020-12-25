<?php

namespace App\Serializer\Normalizer;

use App\Entity\Community;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    private $requestStack;

    public function __construct(ObjectNormalizer $normalizer, RequestStack $requestStack)
    {
        $this->normalizer = $normalizer;
        $this->requestStack = $requestStack;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getFirstName(),
            'avatar' => $object->getAvatar() ? $this->requestStack->getCurrentRequest()->getUriForPath($object->getAvatar()): null,
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
            'communities' => [
                array_map(function (Community $community) {
                    return [
                        'id' => $community->getId(),
                        'name' => $community->getName(),
                        'access' => $community->getAccess(),
                        'city' => $community->getCity(),
                        'count' => $community->getUsers()->count()
                    ];
                },
                    $object->getCommunities()->toArray())
            ]
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
