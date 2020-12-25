<?php

namespace App\Controller;

use App\Entity\Community;
use App\Repository\CommunityRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommunityController extends APIController
{
    /**
     * @Route("/api/communities", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @return JsonResponse
     */
    public function index(CommunityRepository $communityRepository)
    {
        $communities = $communityRepository->findBy([], ['id' => 'DESC']);

        return $this->json($communities);
    }

    /**
     * @Route("/api/communities/{communityId}", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @param Community $communityId
     * @return JsonResponse
     * @throws \Exception
     */
    public function show(CommunityRepository $communityRepository, Community $communityId)
    {
        $this->memberExists($communityId, $this->getUser());
        $community = $communityRepository->find($communityId);

        return $this->json($community);
    }

    /**
     * @Route("/api/communities", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        $community = new Community();
        $community->setData($data);
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
     * @param CommunityRepository $communityRepository
     * @param int $communityId
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(
        Request $request,
        EntityManagerInterface $em,
        CommunityRepository $communityRepository,
        int $communityId
    )
    {
        $this->validateGetParams($communityId, Community::class);
        $data = json_decode($request->getContent(), true);
        $this->memberExists($communityId, $this->getUser());

        $community = $communityRepository->find($communityId);
        $community->setData($data);
        $this->isValid($community);

        $em->persist($community);
        $em->flush();

        return $this->json($community);
    }

    /**
     * @Route("/api/communities/{communityId}", methods={"DELETE"})
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(CommunityRepository $communityRepository, EntityManagerInterface $em, int $communityId)
    {
        $this->validateGetParams($communityId, Community::class);
        $this->memberExists($communityId, $this->getUser());
        $community = $communityRepository->find($communityId);
        $em->remove($community);
        $em->flush();

        return $this->json([
            'success' => 'community successfully deleted',
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/communities/{communityId}/leave", methods={"DELETE"})
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @return JsonResponse
     * @throws \Exception
     */
    public function leave(CommunityRepository $communityRepository, EntityManagerInterface $em, int $communityId)
    {

        $this->validateGetParams($communityId, Community::class);
        $this->memberExists($communityId, $this->getUser());
        $community = $communityRepository->find($communityId);
        $community->removeUser($this->getUser());
        $em->flush();

        return $this->json([
            'success' => 'you left the community',
            'status' => 200
        ]);

    }

    /**
     * @Route("/api/communities/{communityId}/join", methods="POST", requirements={"id":"\d+"})
     * @param Request $request
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @return JsonResponse
     * @throws \Exception
     */
    public function join(
        Request $request,
        CommunityRepository $communityRepository,
        EntityManagerInterface $em,
        int $communityId
    )
    {
        $data = json_decode($request->getContent(), true);
        $this->validateGetParams($communityId, Community::class);
        $this->memberExists($communityId, $this->getUser());
        $community = $communityRepository->find($communityId);
        if ($community->getAccess() !== ($data['access'] ?? null)) {
            return $this->json([
                'error' => 'incorrect access code',
                'status' => 422
            ]);
        }
        $community->addUser($this->getUser());
        $em->flush();

        return $this->json([
            'success' => 'you joined this community',
            'status' => 200
        ]);

    }

    /**
     * @Route("/api/communities/{communityId}/upcoming-events", methods={"GET"})
     * @param EventRepository $eventRepository
     * @param int $communityId
     * @return JsonResponse
     * @throws \Exception
     */
    public function upcomingEvents(EventRepository $eventRepository, int $communityId)
    {
        $this->validateGetParams($communityId, Community::class);
        $this->memberExists($communityId, $this->getUser());

        return $this->json($eventRepository->findByField($communityId, 'community', 5, 'date', 'ASC'), 200);
    }
}
