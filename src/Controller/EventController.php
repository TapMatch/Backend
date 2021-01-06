<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Serializer\Normalizer\EventNormalizer;
use App\Service\OneSignalService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(requirements={"communityId"="\d+", "eventId"="\d+"})
 */
class EventController extends APIController
{
    public function __construct(
        ValidatorInterface $validator
    )
    {
        parent::__construct($validator);
    }

    /**
     * @Route("/api/communities/{community}/events", name="community", methods={"GET"})
     * @param Community $community
     * @return JsonResponse
     * @throws Exception
     */
    public function index(
        Community $community
    ): JsonResponse
    {
        $this->memberExists($this->getUser(), $community->getUsers(),'', true);
        $data = $community->getEvents();

        return $this->json($data, 200);
    }

    /**
     * @Route("/api/communities/{community}/events", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Community $community
     * @return JsonResponse
     * @throws Exception
     */
    public function store(
        Request $request,
        EntityManagerInterface $em,
        Community $community
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->memberExists($this->getUser(), $community->getUsers(), '', true);
        $event = new Event();
        $data['user'] = $this->getUser();
        $event->setData($data);
        $this->isValid($event);

        $em->persist($event);
        $community->addEvent($event);
        $event->addMember($this->getUser());
        $em->flush();

        return $this->json($event, 200);
    }

    /**
     * @Route("/api/communities/{community}/events/{event}/", methods={"GET"})
     * @param EventNormalizer $eventNormalizer
     * @param Community $community
     * @param Event $event
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function show(
        EventNormalizer $eventNormalizer,
        Community $community,
        Event $event
    ): JsonResponse
    {
        $this->memberExists($event, $community->getEvents(), '', true);
        $this->memberExists($this->getUser(), $community->getUsers(), '', true);
        $data = $eventNormalizer->normalize($event);
        $data['last_members'] = array_slice($data['members'], 0, 5);

        return $this->json($data, 200);
    }

    /**
     * @Route("/api/communities/{community}/events/{event}", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Community $community
     * @param Event $event
     * @return JsonResponse
     * @throws Exception
     */
    public function update(
        Request $request,
        EntityManagerInterface $em,
        Community $community,
        Event $event
    ): JsonResponse
    {
        $this->memberExists($event, $community->getEvents(), '', true);
        $data = json_decode($request->getContent(), true);
        $this->memberExists($this->getUser(), $event->getMembers(), '', true);
        $event->setData($data);
        $this->isValid($event);

        $em->persist($event);
        $em->flush();

        return $this->json($event, 200);
    }

    /**
     * @Route("/api/communities/{community}/events/{event}", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param Community $community
     * @param Event $event
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(
        EntityManagerInterface $em,
        Community $community,
        Event $event
    ): JsonResponse
    {
        $this->memberExists($event, $community->getEvents(), '', true);
        $this->memberExists($this->getUser(), $event->getMembers(),'', true);
        $em->remove($event);
        $em->flush();

        return $this->json([
            'message' => 'event successfully deleted',
        ], 200);
    }

    /**
     * @Route("/api/communities/{community}/events/{eventId}/leave", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param Community $community
     * @param int $eventId
     * @param EventRepository $eventRepository
     * @return JsonResponse
     * @throws Exception
     */
    public function leave(
        EntityManagerInterface $em,
        Community $community,
        int $eventId,
        EventRepository $eventRepository
    ): JsonResponse
    {
        $event = $eventRepository->find($eventId);
        if (!$event) {
            return $this->json([
                'error' => 'event with this id does not exist'
            ], 400);
        }
        $this->memberExists($event, $community->getEvents(), '', true);
        $this->memberExists($this->getUser(), $event->getMembers(), '', true);
        $event->removeMember($this->getUser());
        $em->flush();

        return $this->json([
            'message' => 'you left the event'
        ], 200);

    }

    /**
     * @Route("/api/communities/{community}/events/{eventId}/join", methods={"POST"})
     * @param EntityManagerInterface $em
     * @param Community $community
     * @param int $eventId
     * @param EventRepository $eventRepository
     * @param OneSignalService $oneSignalService
     * @return JsonResponse
     * @throws Exception
     */
    public function join(
        EntityManagerInterface $em,
        Community $community,
        int $eventId,
        EventRepository $eventRepository,
        OneSignalService $oneSignalService
    ): JsonResponse
    {
        $event = $eventRepository->find($eventId);
        if (!$event) {
            return $this->json([
               'error' => 'event with this id does not exist'
            ], 400);
        }
        $user = $this->getUser();
//        $this->memberExists($user, $community->getUsers(), '', true);
//        $this->memberExists($event, $community->getEvents(), '', true);
//        $this->memberExists($user, $event->getMembers(), 'event');
        $event->addMember($user);
        $em->flush();
        $oneSignalService->joinedEvent($event, $user);

        return $this->json([
            'message' => 'you joined this event'
        ], 200);
    }
}
