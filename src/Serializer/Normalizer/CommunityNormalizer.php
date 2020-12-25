<?php

namespace App\Serializer\Normalizer;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CommunityNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    private $requestStack;

    public function __construct(ObjectNormalizer $normalizer, RequestStack $requestStack)
    {
        $this->normalizer = $normalizer;
        $this->requestStack = $requestStack;
    }

    /**
     * @param $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'city' => $object->getCity(),
            'is_open' => $object->getIsOpen(),
            'members' => array_map(function (User $user) {
                return [
                    'id' => $user->getId(),
                    'name' => $user->getFirstName(),
                    'phone' => $user->getPhone(),
                    'avatar' => $user->getAvatar() ? $this->requestStack->getCurrentRequest()->getUriForPath($user->getAvatar()): null
                ];
            },
                $object->getUsers()->toArray()),
            'events' =>
                array_map(function (Event $event) {
                    return [
                        'id' => $event->getId(),
                        'name' => $event->getName(),
                        'datetime' => $event->getDate(),
                        'address' => $event->getAddress(),
                        'coordinates' => $event->getCoordinates(),
                        'description' => $event->getDescription(),
                        'join_limit' => $event->getJoinLimit(),
                        'joined' => count($event->getMembers()),
                        'organizer' => $event->getOrganizer()->getId(),

                    ];
                }, $object->getEvents()->toArray())
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
