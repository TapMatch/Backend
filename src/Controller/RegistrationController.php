<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\TokenAuthenticator;
use Authy\AuthyApi;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use function mysql_xdevapi\getSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $authyApi;
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->authyApi = new AuthyApi('CF8gye42YbwBgeCoUMNPLdGKgxgVdzD7');
    }

    /**
     * @Route("/login", name="app_login", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function login(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $newUser = new User();
        $newUser->setPhone($data['phone'] ?? false);
        $newUser->setCountryCode($data['country_code'] ?? false);
        $violations = $validator->validate($newUser);

        if (count($violations) > 0) {
            $errors = [];

            if (isset($violations[0]->getConstraint()->service)) {
                $userRep = $em->getRepository(User::class);
                $user = $userRep->findOneBy(['phone' => $data['phone']]);

                return $this->sendSMS($user);
            }

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse($errors);
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

            return new JsonResponse($authyApiUser->errors());
        }
    }

    public function sendSMS($user)
    {
        $sms = $this->authyApi->requestSms($user->getAuthyId(), ['force' => true]);
        if ($sms->ok()) {
            $this->get('session')->set('user', $user);

            return new JsonResponse([
                'data' => [
                    'phone' => $user->getPhone()
                ],
                'Cookie' => 'PHPSESSID=' . session_id()
            ]);
        }

        return new JsonResponse($sms->errors());

    }

    /**
     * @Route("/verify/resend", name="resend_code", methods={"POST"})
     * @return JsonResponse
     */
    public function resendCode()
    {
        $user = $this->get('session')->get('user');

        return $sms = $this->sendSMS($user);
    }

    /**
     * @Route("/verify/code", name="verify_code", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyCode(Request $request)
    {
        $code = json_decode($request->getContent(), true);

        try {
            // Get data from session
            $data = $this->get('session')->get('user');
            $verification = $this->authyApi->verifyToken($data->getAuthyId(), $code['verify_code']);
            if ($verification->ok()) {
                $user = $this->em->getRepository(User::class)->findOneBy(['phone' => $data->getPhone()]) ?? $data;

                # Create new API key (token)
                $token = bin2hex(random_bytes(16));
                $user->setApiToken($token);
                $user->setLastLogin(new \DateTime());
                $this->em->persist($user);
                $this->em->flush();

                return new JsonResponse($user->getApiToken());
            }

            return new JsonResponse($verification->errors());

        } catch (\Exception $exception) {

            return new JsonResponse(['error' => 'Verification code is incorrect']);
        }
    }
}
