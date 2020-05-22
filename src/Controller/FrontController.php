<?php

namespace App\Controller;

use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_index")
     */
    public function index()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/mesemprunt", name="mes_emprunt", methods={"GET"})
     */
    public function mesEmprunts(EmpruntRepository $empruntRepository)
    {
        $user=$this->getUser();

        $mesemprunts=$empruntRepository->findAll();
        dd($mesemprunts);


    }
}
