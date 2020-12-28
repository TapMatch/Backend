<?php

namespace App\Controller;

use App\Validator\Exists;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param $data
     * @return void
     * @throws Exception
     */
    public function isValid($data)
    {
        $violations = $this->validator->validate($data);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new Exception(json_encode($errors), 422);
        }
    }

    /**
     * @param $needle
     * @param object $haystack
     * @param string $name
     * @param bool $check
     * @throws Exception
     */
    public function memberExists($needle, object $haystack, string $name, bool $check = false)
    {
        if (!$check && in_array($needle, $haystack->toArray())) {
            throw new Exception(json_encode("you've already joined this $name"), 400);
        }elseif ($check && !in_array($needle, $haystack->toArray())) {
            throw new Exception(json_encode('permission denied'), 400);
        }
    }
}
