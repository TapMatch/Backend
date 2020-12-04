<?php

namespace App\Controller;

use App\Entity\Community;
use App\Repository\CommunityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CommunityController extends AbstractController
{
    /**
     * @param $obj
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function serialize($obj)
    {
        $serializer = new Serializer([new ObjectNormalizer()]);

        $data = $serializer->normalize($obj, null, [AbstractNormalizer::ATTRIBUTES => [
            'id',
            'name',
            'events' => [
                'id',
                'name',
                'address',
                'coordinates',
                'description',
                'join_limit'
            ],
            'users' => [
                'id',
                'phone',
                'firstName'
            ]
        ]]);

        return $data;
    }

    /**
     * @Route("/api/communities", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index(CommunityRepository $communityRepository)
    {
        $community = $communityRepository->findAll();

        return new JsonResponse($this->serialize($community));
    }

//    /**
//     * @Route("/api/communities", methods={"POST"})
//     * @param Request $request
//     * @param EntityManagerInterface $em
//     * @return JsonResponse
//     */
//    public function create(Request $request, EntityManagerInterface $em)
//    {
//        $data = json_decode($request->getContent(), true);
//
//        $community = new Community();
//        $community->setData($data);
//        $em->persist($community);
//        $em->flush();
//
//        return new JsonResponse([
//            'data' => $this->serialize($community),
//            'status' => 200
//        ]);
//    }

    /**
     * @Route("/api/communities/{id}", methods={"GET"})
     * @param CommunityRepository $communityRepository
     * @param int $id
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function show(CommunityRepository $communityRepository,int $id)
    {
        $community = $communityRepository->find($id);

        return new JsonResponse($this->serialize($community));

    }

    /**
     * @Route("/api/communities/{id}", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CommunityRepository $communityRepository
     * @param int $id
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function update(Request $request, EntityManagerInterface $em, CommunityRepository $communityRepository,int $id)
    {
        $data = json_decode($request->getContent(), true);

        $community = $communityRepository->find($id);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }
        $community->setData($data);
        $em->persist($community);
        $em->flush();

        return new JsonResponse([
            'data' => $this->serialize($community),
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/communities/{id}", methods={"DELETE"})
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(CommunityRepository $communityRepository, EntityManagerInterface $em, int $id)
    {
        $community = $communityRepository->find($id);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }

        $em->remove($community);
        $em->flush();

        return new JsonResponse([
            'success' => 'community successfully deleted',
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/communities/{id}/leave", methods={"DELETE"})
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $id
     * @return JsonResponse
     */
    public function leave(CommunityRepository $communityRepository, EntityManagerInterface $em, int $id)
    {
        $community = $communityRepository->find($id);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }
        try {

            $community->removeUser($this->getUser());
            $em->flush();

            return new JsonResponse([
                'success' => 'you left the community',
                'status' => 200
            ]);
        } catch(\Exception $exception)
        {
            return new JsonResponse($exception);
        }
    }

    /**
     * @Route("/api/communities/{id}/join", methods={"POST"})
     * @param CommunityRepository $communityRepository
     * @param EntityManagerInterface $em
     * @param int $id
     * @return JsonResponse
     */
    public function join(CommunityRepository $communityRepository, EntityManagerInterface $em, int $id)
    {
        $community = $communityRepository->find($id);

        if (!$community) {
            return new JsonResponse([
                'error' => 'community with this id not found',
                'status' => 422
            ]);
        }
        try {

            $community->addUser($this->getUser());
            $em->flush();

            return new JsonResponse([
                'success' => 'you joined this community',
                'status' => 200
            ]);
        } catch(\Exception $exception)
        {
            return new JsonResponse($exception);
        }
    }

}
