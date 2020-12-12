<?php

namespace App\Controller;

use App\Entity\Community;
use App\Validator\Exists;
use Exception;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class APIController extends AbstractController
{
    private $validator;

    /**
     * APIController constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $id
     * @param $entity
     * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
     * @throws Exception
     */
    public function validateGetParams($id, $entity)
    {
        $constraint = new Exists(['entity' => $entity]);
        $errors = $this->validator->validate($id, $constraint);
        if (count($errors)) {
            throw new Exception($errors[0]->getMessage(), 422);
        }

        return true;
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function isValid($data)
    {
        $violations = $this->validator->validate($data);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json($errors, 422);
        }
    }
}
