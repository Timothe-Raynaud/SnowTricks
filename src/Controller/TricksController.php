<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Tricks;
use App\Entity\Videos;
use App\Form\ImagesFormType;
use App\Form\Type\TricksFormType;
use App\Form\VideosFormType;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/get_tricks", name="get_tricks", methods={"GET"})
     */
    public function getTricks(TricksRepository $tricksRepository): Response
    {
        $tricks = $tricksRepository->getAllTricksWithType();
        $tricksByRow = array_chunk($tricks, 5);

        return $this->json($tricksByRow);
    }

    /**
     * @Route("/add-tricks", name="add_tricks", methods={"GET", "POST"})
     */
    public function addTricks(Request $request): Response
    {
        $trick = new Tricks();

        $form = $this->createForm(TricksFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO -
        }

        return $this->render('pages/tricks/new_trick.html.twig', [
            'trickForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add-image", name="add_image", methods={"GET", "POST"})
     */
    public function addImage(Request $request): Response
    {
        $image = new Images();
        $form = $this->createForm(ImagesFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO -
        }

        return $this->render('pages/tricks/form/_form_image.html.twig', [
            'imagesForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add-video", name="add_video", methods={"GET", "POST"})
     */
    public function addVideo(Request $request): Response
    {
        $video = new Videos();
        $form = $this->createForm(VideosFormType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO -
        }

        return $this->render('pages/tricks/form/_form_video.html.twig', [
            'videosForm' => $form->createView(),
        ]);
    }
}
