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
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'datetime' => $object->getDate(),
            'address' => $object->getAddress(),
            'coordinates' => $object->getCoordinates(),
            'description' => $object->getDescription(),
            'join_limit' => $object->getJoinLimit(),
            'joined' => count($object->getMembers()),
            'organizer' => $object->getOrganizer()->getId(),
            'members' => array_map(function (User $user) use ($context) {
                return [
                    'id' => $user->getId(),
                    'name' => $user->getFirstName(),
                    'phone' => $user->getPhone(),
                    'avatar' => $user->getAvatar() ? $this->requestStack->getCurrentRequest()->getUriForPath($user->getAvatar()): null
                ];
            }, $object->getMembers()->toArray())
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
