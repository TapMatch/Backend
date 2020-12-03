<?php

namespace App\Controller;

use App\Entity\Community;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommunityController extends AbstractController
{
    private $em;
    private $rep;

    /**
     * ProfileController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->rep = $entityManager->getRepository(Community::class);
    }

    /**
     * @Route("/community", name="community", methods={"GET"})
     */
    public function index()
    {
        return new JsonResponse($this->rep->findAll());
    }

    /**
     * @Route("/community/{id}", methods={"GET"})
     * @param $id
     */
    public function show($id)
    {
        $community = $this->rep->find($id);
        $test = $community->getUsers();
    }
}
