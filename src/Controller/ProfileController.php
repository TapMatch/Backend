<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Serializer\Normalizer\UserNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/api/profile/avatar", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function setAvatar(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): JsonResponse
    {
        $file = $request->files->get('photo');
        $validFormat = [
            'jpg',
            'JPG',
            'jpeg',
            'JPEG',
            'png',
            'PNG'
        ];

        $user = $userRepository->findOneBy(['apiToken' => $this->getUser()->getApiToken()]);

        if(!empty($file)) {
            $fileFormat = $file->getClientOriginalExtension();

            if (in_array($fileFormat, $validFormat)) {
                $uploadsBaseUrl = 'assets/img/avatar/';

                $uniqid = md5(uniqid());
                $fileNewName = '/assets/img/avatar/' . $uniqid . '.' . $fileFormat;
                $file->move($uploadsBaseUrl, $fileNewName);

                $user->setAvatar($fileNewName);

                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                return $this->json('file is no valid');
            }

            return $this->json($user, 200);
        }

        return $this->json([
            'error' => 'file is empty',
            'status' => '422',
        ], 422);
    }

    /**
     * @Route("/api/profile", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function updateProfile(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->find($this->getUser());
        $data['finished_onboarding'] ? $user->setFinishedOnboarding($data['finished_onboarding']) : false;
        $data['first_name'] ? $user->setFirstName($data['first_name']) : false;

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'data' => $user,
            'message' => 'success',
            'status' => 200
        ], 200);
    }

    /**
     * @Route("/api/profile/name", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function setName(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->find($this->getUser());
        $user->setFirstName($data['first_name']);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'data' => $user,
            'message' => 'success',
            'status' => 200
        ], 200);
    }

    /**
     * @Route("/api/profile", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getProfile(UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($this->getUser());

        return $this->json($user);
    }
}
