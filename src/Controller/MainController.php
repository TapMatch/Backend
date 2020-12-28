<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): JsonResponse
    {
        return $this->json('');
    }

    /**
     * @Route("/api/tokentest", methods={"POST"})
     */
    public function tokentest(): JsonResponse
    {
        return $this->json($this->getUser());
    }
}
