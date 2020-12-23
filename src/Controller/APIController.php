<?php

namespace App\Controller;

use App\Entity\Community;
use App\Repository\CommunityRepository;
use App\Validator\Exists;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function validateGetParams(...$data)
    {
        $data = array_chunk($data, 2);
        $errors = array_map(function ($item){
            $constraint = new Exists(['entity' => $item[1]]);
            return $errors[] = $this->validator->validate($item[0], $constraint);
        }, $data);
        if (count($errors[0])) {
            $response = array_map(function ($error){
                return $error[0]->getMessage();
            }, $errors);

            throw new Exception(json_encode($response), 422);
        }
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
     * @param $id
     * @param $member
     * @throws Exception
     */
    public function memberExists($id, $member)
    {
        if(!in_array($member, $this->getDoctrine()->getRepository(Community::class)->find($id)->getUsers()->toArray()))
        {
           throw new Exception(json_encode('permission denied'), 400);
        }
    }
}
