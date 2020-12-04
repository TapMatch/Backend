<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\Event;
use App\Repository\CommunityRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EventController extends AbstractController
{
    /**
     * @Route("/api/communities/{communityId}/events", name="community", methods={"GET"})
     * @param EventRepository $eventRepository
     * @param $communityId
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index(EventRepository $eventRepository, $communityId)
    {
        $event = $eventRepository->find($communityId);

        return new JsonResponse($this->serialize($event));
    }

    /**
     * @param $obj
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function serialize($obj)
    {
        $serializer = new Serializer([new ObjectNormalizer()]);

        $data = $serializer->normalize($obj, null, [AbstractNormalizer::ATTRIBUTES => [
            'address',
            'coordinates',
            'description',
            'join_limit',
            'members' => [
                'id',
                'phone',
                'firstName'
            ],
            'organizer' =>
                [
                    'id',
                    'phone',
                    'firstName'
                ]
        ]]);

        return $data;
    }

    /**
     * @Route("/api/communities/{communityId}/events", methods={"POST"})
     * @param Request $request
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function create(Request $request, CommunityRepository $communityRepository, EntityManagerInterface $em, int $communityId)
    {
        $data = json_decode($request->getContent(), true);

        $community = $communityRepository->find($communityId);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }

        $event = new Event();
        $event->setData($data);
        $event->setDate(date_create($data['date']));
        $em->persist($event);
        $community->addEvent($event);
        $em->flush();

        return new JsonResponse([
            'data' => $this->serialize($event),
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @param EventRepository $eventRepository
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function show(CommunityRepository $communityRepository, EventRepository $eventRepository, int $communityId, int $eventId)
    {
        $community = $communityRepository->find($communityId);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }
        $event = $eventRepository->find($eventId);
        return new JsonResponse($event ?
            $this->serialize($event)
            :
            [
                'error' => 'event with this id not found',
                'status' => 422
            ]);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CommunityRepository $communityRepository
     * @param EventRepository $eventRepository
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     */
    public function update(
        Request $request,
        EntityManagerInterface $em,
        CommunityRepository $communityRepository,
        EventRepository $eventRepository, int $communityId,
        int $eventId
    )
    {
        $data = json_decode($request->getContent(), true);

        $community = $communityRepository->find($communityId);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }

        $event = $eventRepository->find($eventId);
        $event->setData($data);
        $em->persist($event);
        $em->flush();

        return new JsonResponse($event ?
            [
                'data' => $this->serialize($event),
                'status' => 200
            ]
            :
            [
                'error' => 'event with this id not found',
                'status' => 422
            ]);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}", methods={"DELETE"})
     * @param CommunityRepository $communityRepository
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     */
    public function destroy(CommunityRepository $communityRepository, EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $community = $communityRepository->find($communityId);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }

        $event = $eventRepository->find($eventId);
        $em->remove($event);
        $em->flush();

        return new JsonResponse($event ?
            [
                'success' => 'event successfully deleted',
                'status' => 200
            ]
            :
            [
                'error' => 'event with this id not found',
                'status' => 422
            ]);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}/leave", methods={"DELETE"})
     * @param CommunityRepository $communityRepository
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     */
    public function leave(CommunityRepository $communityRepository, EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $community = $communityRepository->find($communityId);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }

        $event = $eventRepository->find($eventId);
        $event->removeMember($this->getUser());
        $em->flush();

        return new JsonResponse($event ?
            [
                'success' => 'you left the event',
                'status' => 200
            ]
            :
            [
                'error' => 'event with this id not found',
                'status' => 422
            ]);

    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}/join", methods={"POST"})
     * @param CommunityRepository $communityRepository
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     */
    public function join(CommunityRepository $communityRepository, EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $community = $communityRepository->find($communityId);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }

        $event = $eventRepository->find($eventId);
        $event->addMember($this->getUser());
        $em->flush();

        return new JsonResponse($event ?
            [
                'success' => 'you joined this event',
                'status' => 200
            ]
            :
            [
                'error' => 'event with this id not found',
                'status' => 422
            ]);

    }
}
