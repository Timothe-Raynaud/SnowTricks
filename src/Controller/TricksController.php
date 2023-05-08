<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/getTricks", name="getTricks", methods={"GET"})
     */
    public function getTricks(TricksRepository $tricksRepository): Response
    {
        $tricks = $tricksRepository->getAllTricksWithType();
        $tricksByRow = array_chunk($tricks, 5);

        return $this->json($tricksByRow);
    }
}
