<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LivresController extends AbstractController{
    #[Route('/livres/create', name: 'app_livres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $livre=new Livres();
        $livre->setTitre('Le Seigneur des Anneaux')
              ->setIsbn('111-123-3343-126')
              ->setSlug('le-seigneur-des-anneaux')
              ->setImage('https://picsum.photos/200/?id=3')
              ->setResume('Une épopée fantastique dans un monde remplié de magie, de héros et de créatures mythiques.')
              ->setEditeur('Christian Bourgois')
              ->setDateedition(new \DateTime('1954-07-29'))
              ->setPrix(19.99);
        $em->persist($livre); //préparation de l'objet
        $em->flush(); //Insertion physique
        return new Response("Livre ".$livre->getId()." créé avec succès");
    }

    #[Route('/livres', name: 'app_livres')]
    public function all(LivresRepository $livresRepository): Response
    {
        $livres=$livresRepository->findAll();
        return $this->render('livres/index.html.twig', [
            'livres' => $livres
        ]);
    }

    /* Show without param Convertor
    #[Route('/livres/show/{id}', name: 'app_livres_show')]
    public function show(LivresRepository $rep, $id): Response
    {
        $livre=$rep->find($id);
        if (!$livre)
        {
            throw $this->createNotFoundException("Le livre ".$id." n'existe pas.");
        }
        return $this->render('livres/detail.html.twig', [
            'livre' => $livre
        ]);
    } */

    /* Show with param Convertor */
    #[Route('/livres/show/{id}', name: 'app_livres_show')]
    public function show(Livres $livre): Response
    {
        /* Doesn't work with param convertor
        if (!$livre)
        {
            throw $this->createNotFoundException("Le livre n'existe pas.");
        } */
        return $this->render('livres/detail.html.twig', [
            'livre' => $livre
        ]);
    }

    #[Route('/livres/show2', name: 'app_livres_show2')]
    public function show2(LivresRepository $livresRepository): Response
    {
        $livre=$livresRepository->findOneBy(['titre'=>'Le Seigneur des Anneaux']);
        if (!$livre)
        {
            throw $this->createNotFoundException("Livre non trouvé");
        }
        dd($livre);
    }

    #[Route('/livres/show3', name: 'app_livres_show3')]
    public function show3(LivresRepository $livresRepository): Response
    {
        $livres=$livresRepository->findBy(['titre' => 'Le Seigneur des Anneaux'],
            ['prix' => 'ASC']);
        if (!$livres)
        {
            throw $this->createNotFoundException("Livre non trouvé");
        }
        dd($livres);
    }
    /* Delete without param Convertor
    #[Route('/livres/delete/{id}', name: 'app_livres_delete')]
    public function delete(LivresRepository $rep,EntityManagerInterface $em, $id): Response
    {
        $livre=$rep->find($id);
        $em->remove($livre);
        $em->flush();
        return new Response("Livre $id supprimé avec succès");
    } */
    /* Delete with param Convertor */
    #[Route('/livres/delete/{id}', name: 'app_livres_delete')]
    public function delete(EntityManagerInterface $em, Livres $livre): Response
    {
        $em->remove($livre);
        $em->flush();
        return new Response("Livre supprimé avec succès");
    }
    #[Route('/livres/update/{id}', name: 'app_livres_update')]
    public function update(LivresRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $livre=$rep->find($id);
        $nvPrix=$livre->getPrix()*1.1;
        $livre->setPrix($nvPrix);
        $em->flush();
        return new Response("Livre $id mis à jour avec succès");
    }
}