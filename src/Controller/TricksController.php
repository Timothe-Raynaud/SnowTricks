<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\Type\TricksFormType;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use function PHPUnit\Framework\throwException;

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
    public function addTricks(Request $request, TricksRepository $tricksRepository): Response
    {
        $tricks = new Tricks();

        $form = $this->createForm(TricksFormType::class, $tricks);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($tricksRepository->findOneBy(['name' => $data->getName()]) instanceof Tricks) {
                $this->addFlash('error', 'Un trick avec ce nom existe déja.');
                return $this->render('pages/tricks/new_trick.html.twig', [
                    'trickForm' => $form->createView(),
                ]);
            }

            if ($tricksRepository->addNewFromForm($data)) {
                $this->addFlash('success', 'L\'ajout d\'un nouveau trick à été fait');
                return $this->redirectToRoute('home');
            }

            $this->addFlash('error', 'Trick non importé');
        }

        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $this->addFlash('error', $template);
        }


        return $this->render('pages/tricks/new_trick.html.twig', [
            'trickForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/upload-image", name="upload_image", methods={"POST"})
     */
    public function uploadImage(Request $request, SluggerInterface $slugger): Response
    {
        $now = new \DateTime('now');
        $file = $request->files->get('image');
        $newFilename = '';

        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $now->format('YmdHis') . $safeFilename . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_temporary'),
                    $newFilename
                );
            } catch (FileException $e) {
                throwException($e);
            }

        }

        return $this->json($newFilename);
    }
}
