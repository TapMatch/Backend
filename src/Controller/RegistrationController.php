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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $authyApi;

    public function __construct()
    {
        $this->authyApi = new AuthyApi('CF8gye42YbwBgeCoUMNPLdGKgxgVdzD7');
    }

    /**
     * @return object|UserInterface|null
     * @throws Exception
     */
    public function getUser(): ?UserInterface
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
    public function login(
        Request $request,
        ValidatorInterface $validator,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $check = $userRepository->findOneBy(['phone' => $data['phone']]);
        if ($check) {
            $check->setUuid(isset($data['uuid']) ?: null);
            $em->persist($check);
            $em->flush();

            return $this->sendSMS($check);
        }

        $newUser = new User();
        $newUser->setPhone($data['phone']);
        $newUser->setCountryCode($data['country_code']);
        $newUser->setUuid(isset($data['uuid']) ?: null);
        $violations = $validator->validate($newUser);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json($errors, 422);
        } else {
            $authyApiUser = $this->authyApi->registerUser('test@test.com', $data['phone'], $data['country_code'], false);

            if ($authyApiUser->ok()) {

                $authyId = $authyApiUser->id();
                $newUser->setAuthyId($authyId);

                $em->persist($newUser);
                $em->flush();

                return $this->sendSMS($newUser);
            }

            return $this->json($authyApiUser->message(), 422);
        }
    }

    /**
     * @param $user
     * @return JsonResponse
     */
    public function sendSMS($user): JsonResponse
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
    public function resendCode(): JsonResponse
    {
        return $this->sendSMS($this->getUser());
    }

    /**
     * @Route("/verify/code", name="verify_code", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws Exception
     */
    public function verifyCode(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $code = json_decode($request->getContent(), true);

        // Get data from session
        $data = $this->getUser();
        if($data) {
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
        }

        return $this->json(['error' => !empty($data) ? $verification->message() : 'Incorrect PHPSESSID'], 422);
    }
}
