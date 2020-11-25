<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\TokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(UserPasswordEncoderInterface $passwordEncoder, TokenAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler, Request $request)
    {
        try {

            $data = json_decode($request->getContent(), true);
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->findOneBy(['phone' => $data['phone']]);

            if(!$user) {
                return new JsonResponse('User does not exist');
            }

            // Check Password
            if (!$passwordEncoder->isPasswordValid($user, $data['password'])) {
                return new JsonResponse('Password incorrect');
            }

            // Create new API key (token)
            $token = bin2hex(random_bytes(16));
            $user->setApiToken($token);
//            $user->setLastLogin(new \DateTime());

            $em->persist($user);

            $em->flush();

            return new JsonResponse($user->getApiToken());
//            return new JsonResponse($guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'api' // firewall name in security.yaml
//            ));

        } catch (\Exception $e) {
            return new JsonResponse(array(
                'error' => $e->getMessage()
            ));
        }
    }
}