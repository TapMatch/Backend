<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\Event;
use App\Repository\CommunityRepository;
use App\Repository\EventRepository;
use App\Serializer\Normalizer\EventNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(requirements={"communityId"="\d+", "eventId"="\d+"})
 */
class EventController extends APIController
{
    /**
     * @Route("/api/communities/{communityId}/events", name="community", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @param int $communityId
     * @return Response
     * @throws \Exception
     */
    public function index(CommunityRepository $communityRepository, int $communityId)
    {
        $this->validateGetParams($communityId, Community::class);
        $data = $communityRepository->find($communityId)->getEvents();

        return $this->json($data, 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events", methods={"POST"})
     * @param Request $request
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param EventNormalizer $eventNormalizer
     * @param int $communityId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function store(
        Request $request,
        CommunityRepository $communityRepository,
        EntityManagerInterface $em,
        int $communityId
    )
    {;
        $data = json_decode($request->getContent(), true);
        $this->validateGetParams($communityId, Community::class);
        $community = $communityRepository->find($communityId);
        $event = new Event();
        $data['user'] = $this->getUser();
        $event->setData($data);
        $this->isValid($event);

        $em->persist($event);
        $community->addEvent($event);
        $em->flush();

        return $this->json($event, 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}/", methods={"GET"})
     * @param EventRepository $eventRepository
     * @param EventNormalizer $eventNormalizer
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function show(
        EventRepository $eventRepository,
        EventNormalizer $eventNormalizer,
        int $communityId,
        int $eventId
    )
    {
        $this->validateGetParams($communityId, Community::class);
        $this->validateGetParams($eventId, Event::class);
        $event = $eventRepository->find($eventId);
        $data = $eventNormalizer->normalize($event);
        $data['last_members'] = array_slice($data['members'], 0, 5);

        return $this->json($data, 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventRepository $eventRepository
     * @param EventNormalizer $eventNormalizer
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function update(
        Request $request,
        EntityManagerInterface $em,
        EventRepository $eventRepository,
        int $communityId,
        int $eventId
    )
    {
        $data = json_decode($request->getContent(), true);

        $this->validateGetParams($communityId, Community::class);
        $this->validateGetParams($eventId, Event::class);

        $event = $eventRepository->find($eventId);
        $event->setData($data);
        $this->isValid($event);

        $em->persist($event);
        $em->flush();

        return $this->json($event, 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}", methods={"DELETE"})
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $this->validateGetParams($communityId, Community::class);
        $this->validateGetParams($eventId, Event::class);

        $event = $eventRepository->find($eventId);
        $em->remove($event);
        $em->flush();

        return $this->json([
            'message' => 'event successfully deleted',
        ], 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}/leave", methods={"DELETE"})
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     * @throws \Exception
     */
    public function leave(EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $this->validateGetParams($communityId, Community::class);
        $this->validateGetParams($eventId, Event::class);

        $event = $eventRepository->find($eventId);
        $event->removeMember($this->getUser());
        $em->flush();

        return $this->json([
            'message' => 'you left the event'
        ], 200);

    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}/join", methods={"POST"})
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param Community $communityId
     * @param int $eventId
     * @return JsonResponse
     * @throws \Exception
     */
    public function join(EventRepository $eventRepository, EntityManagerInterface $em, Community $communityId, int $eventId)
    {
        $this->validateGetParams($communityId, Community::class);
        $this->validateGetParams($eventId, Event::class);

        $event = $eventRepository->find($eventId);
        $event->addMember($this->getUser());
        $em->flush();

        return $this->json([
            'message' => 'you joined this event'
        ], 200);

    }
}
