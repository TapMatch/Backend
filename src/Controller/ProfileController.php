<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/api/profile/set-name", methods={"POST"})
     */
    public function setName(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $this->getUser()->getApiToken()]);

        $user->setFirstName($data['name']);

        $em->persist($user);
        $em->flush();

        return new JsonResponse($user);
    }

    /**
     * @Route("/api/profile/set-avatar", methods={"POST"})
     */
    public function setAvatar(Request $request): Response
    {
        $file = $request->files->get('photo');
        $validFormat = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG');
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $this->getUser()->getApiToken()]);

        if(!empty($file)) {
            $fileFormat = $file->getClientOriginalExtension();

            if (in_array($fileFormat, $validFormat)) {
                $uploadsBaseUrl = 'assets/img/avatar/';

                $uniqid = md5(uniqid());
                $fileNewName = $uniqid . '.' . $fileFormat;
                $file->move($uploadsBaseUrl, $fileNewName);

                $user->setAvatar($fileNewName);

                $em->persist($user);
                $em->flush();
            } else {
                return new JsonResponse('file is no valid');
            }

            return new JsonResponse($user);
        }

        return new JsonResponse('file is empty');
    }
}
