<?php

namespace App\Controller\Front;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_index")
     */
    public function index(LivreRepository $livreRepository)
    {
        $user=$this->getUser();

        return $this->render('front/index.html.twig',
        [
            'livres'=>$livreRepository->getLivreForUser($user),
        ]);
    }

    /**
     * @Route("/{id}/emprunt", name="livre_emprunt", methods={"GET"})
     */
    public function emprunter(Livre $livre): Response
    {
        $user=$this->getUser();

        $emprunt=new Emprunt();
        $emprunt->setUser($user)->setLivre($livre);

        $nbre=$livre->getNbre();
        $nbre=$nbre-1;
        $livre->setNbre($nbre);

        if($nbre==0){
            $livre->setDisponibility(false);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($emprunt);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'L\'emprunt a été enregistré  avec succès'
        );

        return $this->redirectToRoute('front_index');
    }

    /**
     * @Route("/{id}/retouner", name="livre_retourner", methods={"GET"})
     */
    public function retourner(Livre $livre, EmpruntRepository $empruntRepository): Response
    {
        $user=$this->getUser();

        $emprunt=$empruntRepository->getEmpruntUser($livre, $user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($emprunt);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Le livre a été retourné avec succès'
        );
        return $this->redirectToRoute('front_index');
    }

    /**
     * @Route("/mesemprunt", name="mes_emprunt", methods={"GET"})
     */
    public function mesEmprunts(LivreRepository $livreRepository)
    {
        $user=$this->getUser();
        return $this->render('front/mes_emprunt.html.twig',[
            'livres'=>$livreRepository->getEmpruntUser($user),
        ]);
    }

}
