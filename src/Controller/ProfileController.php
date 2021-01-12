<?php

namespace App\Controller;

use App\Entity\Cron;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\OneSignalService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/profile/avatar", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function setAvatar(
        Request $request,
        EntityManagerInterface $entityManager
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

        $user = $this->userRepository
            ->findOneBy(['apiToken' => $this->getUser()->getApiToken()]);

        if (!empty($file)) {
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
     * @return JsonResponse
     */
    public function updateProfile(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->find($this->getUser());
        !isset($data['finished_onboarding']) ?: $user->setFinishedOnboarding($data['finished_onboarding']);
        !isset($data['first_name']) ?: $user->setFirstName($data['first_name']);
        !isset($data['uuid']) ?: $user->setUuid($data['uuid']);
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
     * @return JsonResponse
     */
    public function setName(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository
            ->find($this->getUser());
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
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        $user = $this->userRepository
            ->find($this->getUser());

        return $this->json($user);
    }

    /**
     * @Route("/api/profile", methods="DELETE")
     */
    public function destroy(): JsonResponse
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($this->getUser());
            $em->flush();
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        }

        return $this->json([
            'message' => 'successfully deleted'
        ], 200);
    }

    /**
     * @Route("cron", methods="GET")
     * @param OneSignalService $oneSignalService
     * @return JsonResponse
     */
    public function cron(OneSignalService $oneSignalService)
    {
        $oneSignalService->eventStarted();
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->getEndsEvents();
        if ($events) {
            foreach ($events as $event) {
                $this->getDoctrine()->getManager()->remove($event);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->json([], 200);
    }
}
