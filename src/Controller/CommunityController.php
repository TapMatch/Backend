<?php

namespace App\Controller;

use App\Entity\Community;
use App\Repository\CommunityRepository;
use App\Repository\UserRepository;
use App\Serializer\Normalizer\CommunityNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommunityController extends APIController
{
    /**
     * @Route("/api/communities", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @param CommunityNormalizer $communityNormalizer
     * @return JsonResponse
     */
    public function index(CommunityRepository $communityRepository, CommunityNormalizer $communityNormalizer)
    {
        $communities = $communityRepository->findBy([],['id' => 'DESC']);

        return $this->json(array_map(array($communityNormalizer, "normalize"), $communities));
    }

    /**
     * @Route("/api/communities/{communityId}", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @param CommunityNormalizer $communityNormalizer
     * @param int $communityId
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function show(CommunityRepository $communityRepository, CommunityNormalizer $communityNormalizer, int $communityId, ValidatorInterface $validator)
    {
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $community = $communityRepository->find($communityId);

        return $this->json($communityNormalizer->normalize($community));

    }

    /**
     * @Route("/api/communities", methods={"POST"})
     * @param Request $request
     * @param CommunityNormalizer $communityNormalizer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function create(Request $request,CommunityNormalizer $communityNormalizer, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        $community = new Community();
        $community->setIsOpen($data['is_open']);
        $community->setName($data['name']);
        isset($data['access']) ? $community->setAccess($data['access']) : false;
        $em->persist($community);
        $em->flush();

        return new JsonResponse([
            'data' => $communityNormalizer->normalize($community),
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/communities/{communityId}", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CommunityRepository $communityRepository
     * @param CommunityNormalizer $communityNormalizer
     * @param int $communityId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function update(
        Request $request,
        EntityManagerInterface $em,
        CommunityRepository $communityRepository,
        CommunityNormalizer $communityNormalizer,
        int $communityId
    )
    {
        $data = json_decode($request->getContent(), true);
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }

        $community = $communityRepository->find($communityId);
        $community->setData($data);
        $em->persist($community);
        $em->flush();

        return $this->json($communityNormalizer->normalize($community));
    }

    /**
     * @Route("/api/communities/{communityId}", methods={"DELETE"})
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @return JsonResponse
     */
    public function destroy(CommunityRepository $communityRepository, EntityManagerInterface $em, int $communityId)
    {
        $check = $this->validateGetParams($communityId, Community::class);

        if ($check) {
            return $check;
        }
        $community = $communityRepository->find($communityId);

        $em->remove($community);
        $em->flush();

        return new JsonResponse([
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
     */
    public function leave(CommunityRepository $communityRepository, EntityManagerInterface $em, int $communityId)
    {

        $check = $this->validateGetParams($communityId, Community::class);
        if ($check) {
            return $check;
        }

        $community = $communityRepository->find($communityId);
        $community->removeUser($this->getUser());
        $em->flush();

        return new JsonResponse([
            'success' => 'you left the community',
            'status' => 200
        ]);

    }

    /**
     * @Route("/api/communities/{communityId}/join", methods={"POST"})
     * @param Request $request
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $communityId
     * @return JsonResponse
     */
    public function join(Request $request, CommunityRepository $communityRepository, EntityManagerInterface $em, int $communityId)
    {
        $data = json_decode($request->getContent(), true);
        $check = $this->validateGetParams($communityId, Community::class);
        if ($check) {
            return $check;
        }

        $community = $communityRepository->find($communityId);
        if($community->getAccess() !== ($data['access'] ?? null))
        {
            return $this->json([
                'error' => 'incorrect code',
                'status' => 422
            ]);
        }
        $community->addUser($this->getUser());
        $em->flush();

        return new JsonResponse([
            'success' => 'you joined this community',
            'status' => 200
        ]);

    }

}
