<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Serializer\Normalizer\EventNormalizer;
use App\Service\OneSignalService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route(requirements={"communityId"="\d+", "eventId"="\d+"})
 */
class EventController extends APIController
{
    /**
     * @Route("/api/communities/{community}/events", name="community", methods={"GET"})
     * @param Community $community
     * @param EventNormalizer $eventNormalizer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function index(
        Community $community,
        EventNormalizer $eventNormalizer
    ): ?JsonResponse
    {
        $this->memberExists($this->getUser(), $community->getUsers(),'', true);
        $data = [];
        foreach ($community->getEvents() as $event) {
            $data[] = $eventNormalizer->normalize($event, 'json', ['index']);
        }

        return new JsonResponse($data, 200);
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
        $data['date'] = \DateTime::createFromFormat('Y-d-m H:i', $data['date'], new \DateTimeZone($data['user']->getTimezone()))
            ->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $event->setData($data);
        $this->isValid($event);

        $em->persist($event);
        $community->addEvent($event);
        $event->addMember($this->getUser());
        $em->flush();

        return $this->json($event, 200);
    }

    /**
     * @Route("/api/communities/{community}/events/{event}/", methods="GET")
     * @param Community $community
     * @param Event $event
     * @return JsonResponse
     * @throws Exception
     */
    public function show(
        Community $community,
        Event $event
    ): JsonResponse
    {
        $this->memberExists($event, $community->getEvents(), '', true);
        $this->memberExists($this->getUser(), $community->getUsers(), '', true);

        return $this->json($event, 200);
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
        $this->memberExists($user, $community->getUsers(), '', true);
        $this->memberExists($event, $community->getEvents(), '', true);
        $this->memberExists($user, $event->getMembers(), 'event');
        if ($event->getMembers()->count() < $event->getJoinLimit()) {
            $event->addMember($user);
            $em->flush();
            if($user->getId() !== $event->getOrganizer()->getId()) {
                $oneSignalService->joinedEvent($event, $user);
            }

            return $this->json([
                'message' => 'you joined this event'
            ], 200);
        }
        return $this->json([
            'error' => 'event is full'
        ], 422);
    }
}
