<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    private $em;
    private $rep;

    /**
     * ProfileController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->rep = $entityManager->getRepository(User::class);
    }

    /**
     * @Route("/api/profile/{id}/avatar", methods={"PUT"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function setAvatar(Request $request, $id): Response
    {
        $file = $request->files->get('photo');
        $validFormat = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG');

        $user = $this->rep->findOneBy(['apiToken' => $this->getUser()->getApiToken()]);

        if(!empty($file)) {
            $fileFormat = $file->getClientOriginalExtension();

            if (in_array($fileFormat, $validFormat)) {
                $uploadsBaseUrl = 'assets/img/avatar/';

                $uniqid = md5(uniqid());
                $fileNewName = $uniqid . '.' . $fileFormat;
                $file->move($uploadsBaseUrl, $fileNewName);

                $user->setAvatar($fileNewName);

                $this->em->persist($user);
                $this->em->flush();
            } else {
                return new JsonResponse('file is no valid');
            }

            return new JsonResponse($user);
        }

        return new JsonResponse('file is empty');
    }

    /**
     * @Route("/profile/{id}", methods={"PUT"})
     * @param Request $request
     * @param User $id
     * @return JsonResponse
     */
    public function updateProfile(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->rep->find($id);
        $user->setData($data);

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'success',
            'status' => 200
        ]);
    }
}
