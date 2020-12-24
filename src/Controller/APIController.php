<?php

namespace App\Controller;

use App\Entity\Community;
use App\Repository\CommunityRepository;
use App\Validator\Exists;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use http\Client\Response;
use function MongoDB\BSON\toJSON;
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
     * @return void
     * @throws Exception
     */
    public function validateGetParams($id, $entity)
    {
            $constraint = new Exists(['entity' => $entity]);
            $errors = $this->validator->validate($id, $constraint);
        if (count($errors)) {
            throw new Exception($errors[0]->getMessage(), 422);
        }
    }

    /**
     * @param $data
     * @param bool $constraint
     * @return void
     * @throws Exception
     */
    public function isValid($data)
    {
        $violations = $this->validator->validate($data);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath() ?: 'Exists'] = $violation->getMessage();
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
