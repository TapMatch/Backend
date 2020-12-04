<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProfileController extends AbstractController
{
    /**
     * @Route("/api/profile/avatar", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function setAvatar(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $file = $request->files->get('photo');
        $validFormat = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG');

        $user = $userRepository->findOneBy(['apiToken' => $this->getUser()->getApiToken()]);

        if(!empty($file)) {
            $fileFormat = $file->getClientOriginalExtension();

            if (in_array($fileFormat, $validFormat)) {
                $uploadsBaseUrl = 'assets/img/avatar/';

                $uniqid = md5(uniqid());
                $fileNewName = $uniqid . '.' . $fileFormat;
                $file->move($uploadsBaseUrl, $fileNewName);

                $user->setAvatar($fileNewName);

                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                return new JsonResponse('file is no valid');
            }

            return new JsonResponse($user);
        }

        return new JsonResponse('file is empty');
    }

    /**
     * @Route("/api/profile", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function updateProfile(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true);

        $user = $userRepository->find($this->getUser());
        $user->setData($data);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'data' => $user,
            'message' => 'success',
            'status' => 200
        ]);
    }

    /**
     * @Route("/api/profile", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getProfile(UserRepository $userRepository)
    {
        $user = $userRepository->find($this->getUser());

        $serializer = new Serializer([new ObjectNormalizer()]);

        $data = $serializer->normalize($user, null, [AbstractNormalizer::ATTRIBUTES => [
            'id',
            'name',
            'phone',
            'firstName',
            'events' => [
                'id',
                'name',
                'address',
                'coordinates',
                'description',
                'join_limit'
            ],
            'communities' => [
                'id',
                'name'
            ]
        ]]);

        return new JsonResponse([
            'data' => $data,
            'message' => 'success',
            'status' => 200
        ]);
    }
}
