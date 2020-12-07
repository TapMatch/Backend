<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Exists */
        $repository = $this->em->getRepository($constraint->entity);
        if (empty($value)) {
            return;
        }
        if ($repository->find($value) === null) {
            $entityName = explode('\\', $constraint->entity);
            $this->context->buildViolation(end($entityName) . $constraint->message)
                ->addViolation();
        }
    }
}
