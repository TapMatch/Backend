<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\TokenAuthenticator;
use Authy\AuthyApi;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $authyApi;

    public function __construct()
    {
        $this->authyApi = new AuthyApi(getenv('TWILIO_AUTHY_API_KEY'));
    }

    /**
     * @Route("/register", name="app_register", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function register(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);
        $newUser = new User();
        $newUser->setPhone($data['phone'] ?? false)
            ->setCountryCode($data['country_code'] ?? null)
            ->setPassword($data['password'] ?? false);
        $violations = $validator->validate($newUser);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation)
            {
                $errors [$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse($errors);
        }

        $cookie = $request->cookies->get('PHPSESSID');
        $user = $this->authyApi->registerUser('test@test.com', $data['phone'], $data['country_code']);

        if ($user->ok()) {
            $sms = $this->authyApi->requestSms($user->id(), ["force" => "true"]);
            if ($sms->ok()) {
                $this->addFlash(
                    'success',
                    $sms->message()
                );
            }

            $this->get('session')->set('authyId', $user->id());
            $this->get('session')->set('user', $newUser);
        }

        return new JsonResponse($user->ok() ?
            [
                'data' => $newUser,
                'Cookie' => 'PHPSESSID = '.$cookie
            ]
            :
            $user->errors());
    }

    /**
     * @Route("/verify/resend", name="resend_code", methods={"GET"})
     * @return JsonResponse
     */
    public function resendCode()
    {
        $authyId = $this->get('session')->get('authyId');
        $sms = $this->authyApi->requestSms($authyId, ["force" => "true"]);

        if ($sms->ok()) {
            $this->addFlash(
                'success',
                $sms->message()
            );

            return new JsonResponse(['message' => 'Verification code sent']);

        }

        return new JsonResponse(['error' => 'something went wrong']);
    }

    /**
     * @Route("/verify/code", name="verify_code", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function verifyCode(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $code = json_decode($request->getContent(), true);

        try {
            // Get data from session
            $authyId = $this->get('session')->get('authyId');
            $user = $this->get('session')->get('user');
            $verification = $this->authyApi->verifyToken($authyId, $code['verify_code']);
            $newUser = $this->saveUser($user);

            return new JsonResponse($newUser);

        } catch (\Exception $exception) {
            $this->addFlash(
                'error',
                'Verification code is incorrect'
            );

            return new JsonResponse(['error' => 'Verification code is incorrect']);
        }
    }

    public function saveUser($user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setIsVerified(true);
        $this->addFlash(
            'success',
            'You phone number has been verified. Log in here'
        );

        // save user
        $em->persist($user);
        $em->flush();
    }
}
