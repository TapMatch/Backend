<?php

namespace App\Controller;

use App\Entity\Community;
use App\Repository\CommunityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommunityController extends APIController
{
    private CommunityRepository $communityRepository;

    /**
     * CommunityController constructor.
     * @param CommunityRepository $communityRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        CommunityRepository $communityRepository,
        ValidatorInterface $validator
    )
    {
        parent::__construct($validator);
        $this->communityRepository = $communityRepository;
    }

    /**
     * @Route("/api/communities", methods={"GET"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $communities = $this->communityRepository->findBy([], ['id' => 'DESC']);

        return $this->json($communities);
    }

    /**
     * @Route("/api/communities/{communityId}", methods={"GET"})
     * @param Community $communityId
     * @return JsonResponse
     * @throws Exception
     */
    public function show(Community $communityId): JsonResponse
    {
        return $this->json($communityId);
    }

    /**
     * @Route("/api/communities", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws Exception
     */
    public function store(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $community = new Community();
        $community->setData($data);
        $community->addUser($this->getUser());
        $this->isValid($community);
        $em->persist($community);
        $em->flush();

        return $this->json([
            'data' => $community,
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/communities/{communityId}", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Community $communityId
     * @return JsonResponse
     * @throws Exception
     */
    public function update(
        Request $request,
        EntityManagerInterface $em,
        Community $communityId
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->memberExists($this->getUser(), $communityId->getUsers(), '', true);

        $communityId->setData($data);
        $this->isValid($communityId);

        $em->persist($communityId);
        $em->flush();

        return $this->json($communityId);
    }

    /**
     * @Route("/api/communities/{community}", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param Community $community
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(
        EntityManagerInterface $em,
        Community $community
    ): JsonResponse
    {
        $this->memberExists($this->getUser(), $community->getUsers(), '', true);
        $em->remove($community);
        $em->flush();

        return $this->json([
            'success' => 'community successfully deleted',
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/communities/{communityId}/leave", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param Community $communityId
     * @return JsonResponse
     * @throws Exception
     */
    public function leave(
        EntityManagerInterface $em,
        Community $communityId
    ): JsonResponse
    {
        $this->memberExists($this->getUser(), $communityId->getUsers(), '', true);
        $communityId->removeUser($this->getUser());
        $em->flush();

        return $this->json([
            'success' => 'you left the community',
            'status' => 200
        ]);

    }

    /**
     * @Route("/api/communities/{communityId}/join", methods="POST", requirements={"id":"\d+"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Community $communityId
     * @return JsonResponse
     * @throws Exception
     */
    public function join(
        Request $request,
        EntityManagerInterface $em,
        Community $communityId
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->memberExists($this->getUser(), $communityId->getUsers(), 'community');
        if (isset($data['access']) && $communityId->getAccess() == $data['access'] || $communityId->getIsOpen()) {
            $communityId->addUser($this->getUser());
            $em->flush();

            return $this->json([
                'success' => 'you joined this community',
                'status' => 200
            ]);

        }

        return $this->json([
            'error' => 'incorrect access code',
        ], 422);

    }

    /**
     * @Route("/api/communities/{communityId}/upcoming-events", methods={"GET"})
     * @return JsonResponse
     * @throws Exception
     */
    public function upcomingEvents(): JsonResponse
    {
        $events = $this->getUser()->getEvents()->slice(0, 5);
        return $this->json([
            'count' => count($events),
            'data' => $events
        ]);
    }
}
