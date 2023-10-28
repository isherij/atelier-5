<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\MinmaxType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
    );

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'showAuthor')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        return $this->render('author/list.html.twig', [
            'authors' => $this->authors,
        ]);
    }

    #[Route('/auhtorDetails/{id}', name: 'auhtorDetails')]
    public function auhtorDetails($id): Response
    {
        $author = null;
        foreach ($this->authors as $autherD) {
            if ($autherD['id'] == $id) {
                $author = $autherD;
            }
        }
        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }
    //

    //afficher la table Author
    #[Route('/showDBauthor', name: 'showDBauthor')]
    public function showDBauthor(AuthorRepository $AuthorRepository,ManagerRegistry $ManagerRegistry,Request $Req): Response
    {
        $em = $ManagerRegistry->getManager();
        //$author = $AuthorRepository->findall();
        //$author = $AuthorRepository->orderbyusername();
       // $author = $AuthorRepository->sarchwithalph();
        $author = $AuthorRepository->showWithAlphabetADR_MAIL();
        $form = $this->createForm(MinmaxType::class);
            $form->handleRequest($Req);
             if ($form->isSubmitted())
            {
            $min=$form->get('min')->getData();
            $max=$form->get('max')->getData();
            $authors = $AuthorRepository->MinMax($min,$max);
            return $this->renderForm('author/showDBauthor.html.twig', [
                'author' => $authors,
                'f' => $form
            ]);
            
            }
        return $this->renderForm('author/showDBauthor.html.twig', [
            'author' => $author,
            'f' => $form
        ]);
    }

    #[Route('/RomoveAuthorsAvecZeroBooks', name: 'RomoveAuthorsAvecZeroBooks')]
    public function RomoveAuthorsAvecZeroBooks(AuthorRepository $AuthorRepository): Response
    {
        $AuthorRepository->RomoveAuthorsAvecZeroBooks();  
        return $this->redirectToRoute('showDBauthor');
    }

    //ajouter un author en creant un FORM
    #[Route('/addDBauthor', name: 'addDBauthor')]
    public function addDBauthor(ManagerRegistry $ManagerRegistry, Request $Req): Response
    {
        $em = $ManagerRegistry->getManager();
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($Req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('showDBauthor');
        }
        return $this->renderForm('author/addformauthor.html.twig', [
            'f' =>$form
        ]);
    }

    //update d 'un author en creant aussi un FORM
    #[Route('/editDBauthor/{id}', name: 'editDBauthor')]
    public function editDBauthor($id,AuthorRepository $AuthorRepository,ManagerRegistry $ManagerRegistry, Request $Req): Response
    {
        $em = $ManagerRegistry->getManager();
        $dataid = $AuthorRepository->find($id);
        $form = $this->createForm(AuthorType::class, $dataid);
        $form->handleRequest($Req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showDBauthor');
        }
        return $this->renderForm('author/editDBauthor.html.twig', [
            'frm' =>$form
        ]);
    }

        //REMOVE d 'un author
        #[Route('/REMOVEDBauthor/{id}', name: 'REMOVEDBauthor')]
        public function REMOVEDBauthor($id,AuthorRepository $AuthorRepository,ManagerRegistry $ManagerRegistry): Response
        {
            $em = $ManagerRegistry->getManager();
            $dataid = $AuthorRepository->find($id);
            $em->remove($dataid);
            $em->flush();
         
            return $this->redirectToRoute('showDBauthor');
        }

       
}
