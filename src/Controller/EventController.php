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

class EventController extends APIController
{
    /**
     * @Route("/api/communities/{communityId}/events", name="community", methods={"GET"})
     * @param EventRepository $eventRepository
     * @param EventNormalizer $eventNormalizer
     * @param $communityId
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index(EventRepository $eventRepository, EventNormalizer $eventNormalizer, $communityId)
    {
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $event = $eventRepository->find($communityId);
        $data = $eventNormalizer->normalize($event);
        $data['members'] = $event->getMembers();

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
     */
    public function create(
        Request $request,
        CommunityRepository $communityRepository,
        EntityManagerInterface $em,
        EventNormalizer $eventNormalizer,
        int $communityId
    )
    {
        $data = json_decode($request->getContent(), true);
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $community = $communityRepository->find($communityId);
        $event = new Event();
        $event->setData($data);
        $event->setOrganizer($this->getUser());
        $event->setDate(date_create($data['date']));
        $em->persist($event);
        $community->addEvent($event);
        $em->flush();

        return $this->json($eventNormalizer->normalize($event), 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}", methods={"GET"})
     * @param EventRepository $eventRepository
     * @param EventNormalizer $eventNormalizer
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function show(
        EventRepository $eventRepository,
        EventNormalizer $eventNormalizer,
        int $communityId,
        int $eventId
    )
    {
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $event = $eventRepository->find($eventId);

        $test = array_slice($event->getMembers(), 0, 5);
        $data = $eventNormalizer->normalize($event);
        $data['last_members'] = $test;

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
     */
    public function update(
        Request $request,
        EntityManagerInterface $em,
        EventRepository $eventRepository,
        EventNormalizer $eventNormalizer,
        int $communityId,
        int $eventId
    )
    {
        $data = json_decode($request->getContent(), true);

        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $event = $eventRepository->find($eventId);
        $event->setData($data);
        $em->persist($event);
        $em->flush();

        return $this->json($eventNormalizer->normalize($event), 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}", methods={"DELETE"})
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     */
    public function destroy(EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $event = $eventRepository->find($eventId);
        $em->remove($event);
        $em->flush();

        return $this->json([
            'message' => 'event successfully deleted',
            'success' => 'ok'
        ], 200);
    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}/leave", methods={"DELETE"})
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     */
    public function leave(EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $event = $eventRepository->find($eventId);
        $event->removeMember($this->getUser());
        $em->flush();

        return $this->json([
            'message' => 'you left the event',
            'success' => 'ok'
        ], 200);

    }

    /**
     * @Route("/api/communities/{communityId}/events/{eventId}/join", methods={"POST"})
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @param int $eventId
     * @return JsonResponse
     */
    public function join(EventRepository $eventRepository, EntityManagerInterface $em, int $communityId, int $eventId)
    {
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $event = $eventRepository->find($eventId);
        $event->addMember($this->getUser());
        $em->flush();

        return $this->json([
                'message' => 'you joined this event',
                'success' => 'ok'
            ], 200);

    }
}
