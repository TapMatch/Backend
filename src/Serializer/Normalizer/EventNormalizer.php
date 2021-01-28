<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EventNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    private $requestStack;

    private $tokenStorage;

    /**
     * EventNormalizer constructor.
     * @param ObjectNormalizer $normalizer
     * @param RequestStack $requestStack
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ObjectNormalizer $normalizer, RequestStack $requestStack, TokenStorageInterface $tokenStorage)
    {
        $this->requestStack = $requestStack;
        $this->normalizer = $normalizer;
        $this->tokenStorage = $tokenStorage;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $default = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'coordinates' => $object->getCoordinates(),
            'join_limit' => $object->getJoinLimit(),
            'joined' => count($object->getMembers()),
            'community_id' => $object->getCommunity()->getId(),
            'organizer' => [
                'id' => $object->getOrganizer()->getId(),
                'avatar' => $object->getOrganizer()->getAvatar()
                    ? $this->requestStack->getCurrentRequest()->getUriForPath($object->getOrganizer()->getAvatar())
                    : null,
            ]
        ];

        $additional = [
            'datetime' => $object->getDate()->setTimezone(new \DateTimeZone($user->getTimezone())),
            'address' => $object->getAddress(),
            'description' => $object->getDescription(),
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

        $object->getMembers()->removeElement($object->getOrganizer());

        $last_members = [
            'last_members' => array_values($object->getMembers()->map(function (User $user) {
                return [
                    'id' => $user->getId(),
                    'avatar' => $user->getAvatar() ? $this->requestStack->getCurrentRequest()->getUriForPath($user->getAvatar()) : null
                ];
            })->slice(0, 5))
        ];

        return in_array('index', $context)
            ? array_merge_recursive($default, $last_members)
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
