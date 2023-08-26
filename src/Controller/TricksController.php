<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\Type\TricksFormType;
use App\Repository\TricksRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @Route("/get_tricks", name="get_tricks", methods={"POST"})
     */
    public function getTricksTest(Request $request, TricksRepository $tricksRepository): Response
    {
        if (!empty($request->request->get('loaderModule'))){
            $tricks = $tricksRepository->getAllTricksWithType();

            $html = [];
            foreach($tricks as $trick){
                $html[] = $this->renderView('pages/tricks/_card.html.twig', [
                    'trick' => $trick
                ]);
            }
            return $this->json($html);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/add-tricks", name="add_tricks", methods={"GET", "POST"})
     */
    public function addTricks(Request $request, TricksRepository $tricksRepository): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('user_login');
        }
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

    /**
     * @Route("/trick/detail", name="get_trick_detail_fetch", methods={"POST"})
     * @throws NonUniqueResultException
     */
    public function getTrickFetch(Request $request, TricksRepository $tricksRepository): Response
    {
        $slug = $request->request->get('pushModule');

        if ($slug){
            $trick = $tricksRepository->findTrickBySlugWithMedia($slug);
            if (!$trick) {
                throw $this->createNotFoundException('Trick not found');
            }

            $html = $this->renderView('pages/tricks/_modal.html.twig', [
                'trick' => $trick
            ]);

            return $this->json($html);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/detail/{slug}", name="get_trick_detail", methods={"GET"})
     */
    public function getTrick(String $slug): Response
    {
        return $this->redirectToRoute('home' , []);
    }
}
