<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EventNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    private $requestStack;

    /**
     * EventNormalizer constructor.
     * @param ObjectNormalizer $normalizer
     * @param RequestStack $requestStack
     */
    public function __construct(ObjectNormalizer $normalizer, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $default = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'coordinates' => $object->getCoordinates(),
            'community_id' => $object->getCommunity()->getId(),
            'organizer' => [
                'id' => $object->getOrganizer()->getId(),
                'avatar' => $object->getOrganizer()->getAvatar()
                    ? $this->requestStack->getCurrentRequest()->getUriForPath($object->getOrganizer()->getAvatar())
                    : null,
            ]
        ];

        $additional = [
            'datetime' => $object->getDate(),
            'address' => $object->getAddress(),
            'description' => $object->getDescription(),
            'join_limit' => $object->getJoinLimit(),
            'joined' => count($object->getMembers()),
            'organizer' => [
                'name' => $object->getOrganizer()->getFirstName(),
            ],
            'members' => array_map(function (User $user) use ($context) {
                return [
                    'id' => $user->getId(),
                    'name' => $user->getFirstName(),
                    'phone' => $user->getPhone(),
                    'avatar' => $user->getAvatar() ? $this->requestStack->getCurrentRequest()->getUriForPath($user->getAvatar()) : null
                ];
            }, $object->getMembers()->toArray())

        ];

        $last_members = [
            'last_members' => array_map(function (User $user) {
                return [
                    'id' => $user->getId(),
                    'avatar' => $user->getAvatar() ? $this->requestStack->getCurrentRequest()->getUriForPath($user->getAvatar()) : null
                ];
            }, $object->getMembers()->slice(0, 5))
        ];

        return in_array('index', $context)
            ? $default + $last_members
            : array_merge_recursive($default, $additional);
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
