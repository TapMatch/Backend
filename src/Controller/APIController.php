<?php

namespace App\Controller;

use App\Validator\Exists;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class APIController extends AbstractController
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateGetParams($id, $entity)
    {
        $Constraint = new Exists(['entity' => $entity]);
        $errors = $this->validator->validate($id, $Constraint);
        if (count($errors)) {
            return $this->json([
                'error' => $errors[0]->getMessage(),
                'status' => 422
            ]);
        }

        return false;
    }
}
