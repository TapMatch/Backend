<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Authy\AuthyApi;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $authyApi;

    public function __construct()
    {
        $this->authyApi = new AuthyApi('CF8gye42YbwBgeCoUMNPLdGKgxgVdzD7');
    }

    /**
     * @return object|\Symfony\Component\Security\Core\User\UserInterface|null
     * @throws Exception
     */
    public function getUser()
    {
        $user = $this->get('session')->get('user');
        if (!$user) {
            throw new Exception('incorrect PHPSESSID');
        }

        return $user;
    }

    /**
     * @Route("/login", name="app_login", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function login(Request $request, ValidatorInterface $validator, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        $newUser = new User();
        $newUser->setPhone($data['phone'] ?? false);
        $newUser->setCountryCode($data['country_code'] ?? false);
        $violations = $validator->validate($newUser);

        if (count($violations) > 0) {
            $errors = [];

            if (isset($violations[0]->getConstraint()->service)) {
                $user = $userRepository->findOneBy(['phone' => $data['phone']]);

                return $this->sendSMS($user);
            }

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json($errors, 422);
        } else {
            $authyApiUser = $this->authyApi->registerUser('test@test.com', $data['phone'], $data['country_code'], false);

            if ($authyApiUser->ok()) {

                $authyId = $authyApiUser->id();
                $newUser->setAuthyId($authyId);

                // save user
                $em->persist($newUser);
                $em->flush();

                return $this->sendSMS($newUser);
            }

            return $this->json($authyApiUser->errors(), 422);
        }
    }

    /**
     * @param $user
     * @return JsonResponse
     */
    public function sendSMS($user)
    {
        $sms = $this->authyApi->requestSms($user->getAuthyId(), ['force' => true]);

        if ($sms->ok()) {
            $this->get('session')->set('user', $user);

            return $this->json([
                'data' => [
                    'phone' => $user->getPhone()
                ],
                'Cookie' => 'PHPSESSID=' . session_id()
            ], 200);
        }

        return $this->json($sms->message(), 400);

    }

    /**
     * @Route("/verify/resend", name="resend_code", methods={"POST"})
     * @return JsonResponse
     * @throws Exception
     */
    public function resendCode()
    {
        $user = $this->getUser();

        return $this->sendSMS($user);
    }

    /**
     * @Route("/verify/code", name="verify_code", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws Exception
     */
    public function verifyCode(Request $request, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $code = json_decode($request->getContent(), true);

        // Get data from session
        $data = $this->getUser();

        $verification = $this->authyApi->verifyToken($data->getAuthyId(), $code['verify_code']);
        if ($verification->ok()) {
            $user = $userRepository->findOneBy(['phone' => $data->getPhone()]) ?? $data;

            # Create new API key (token)
            $token = bin2hex(random_bytes(16));
            $user->setApiToken($token);
            $user->setLastLogin(new \DateTime());
            $em->persist($user);
            $em->flush();

            return $this->json($user->getApiToken(), 200);
        }

        return $this->json($verification->errors(), 422);

    }
}
