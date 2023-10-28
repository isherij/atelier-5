<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

        //afficher la table Book
        #[Route('/showDBBook', name: 'showDBBook')]
        public function showDBBook(BookRepository $BookRepository, Request $Req): Response
        {
            $book = $BookRepository->findBy(['Published' => true]);
           //$romance='Romance';
           //$book = $BookRepository->findAll();
            $tt_unpublished_books = count($BookRepository->findBy(['Published' => false]));
            $form = $this->createForm(SearchType::class);
            $form->handleRequest($Req);
        //     if ($form->isSubmitted())
        //    {
        //     $data=$form->get('ref')->getData();
        //     $books = $BookRepository->SearchBookWithRef($data);
        //     return $this->renderForm('book/showDBBook.html.twig', [
        //         'book' => $books,
        //         'tt_unpublished_books' => $tt_unpublished_books,
        //         'f' => $form
        //     ]);
        //    }  
           
           //$book = $BookRepository->orderbyAuthorName();
          // $book = $BookRepository->findBooksPublishedBefore2023WhereAuthorsWhoHaveMoreThan35Books();
          
          //$BookRepository->updateCategoryForWilliamShakespeare($romance);
          $TotalNumberOfScienceFictionBooks = $BookRepository->TheTotalSumOfScienceFictionBooks(); 
          $book = $BookRepository->findBooksPublishedBetweenTwoDates();
            return $this->renderForm('book/showDBBook.html.twig', [
                'book' => $book,
                'tt_unpublished_books' => $tt_unpublished_books,
                'f' => $form,
                'TotalNumberOfScienceFictionBooks' => $TotalNumberOfScienceFictionBooks
            ]);
        }

        
  #[Route('/findBooksPublishedBetweenTwoDates', name: 'findBooksPublishedBetweenTwoDates')]
  public function findBooksPublishedBetweenTwoDates(BookRepository $BookRepository): Response
  {
  
    $book = $BookRepository->findBooksPublishedBetweenTwoDates();
      return $this->renderForm('book/findBooksPublishedBetweenTwoDates.html.twig', [
          'book' => $book,
      ]);
  }
            //ajouter un book en creant un FORM
    #[Route('/addDBbook', name: 'addDBbook')]
    public function addDBbook(ManagerRegistry $ManagerRegistry, Request $Req): Response
    {
        $em = $ManagerRegistry->getManager();
        $book = new Book();
        $book->setPublished(true);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($Req);
      
        if ($form->isSubmitted() and $form->isValid()) {
           
            $author=$book->getAuthor();
            if($author instanceof Author) //verifier qu'il est un objet d'une classe specifique
            $author->setNbbooks($author->getNbbooks()+1);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('showDBBook');
        }
        return $this->renderForm('book/addformbook.html.twig', [
            'f' =>$form
        ]);
    }

                //edit un book en creant un FORM
                #[Route('/editDBbook/{ref}', name: 'editDBbook')]
                public function editDBbook($ref,BookRepository $BookRepository,ManagerRegistry $ManagerRegistry, Request $Req): Response
                {
                    $em = $ManagerRegistry->getManager();
                    $dataid = $BookRepository->find($ref);
                    $form = $this->createForm(BookType::class, $dataid);
                    $form->handleRequest($Req);
                    if ($form->isSubmitted() and $form->isValid()) {
                        $em->persist($dataid);
                        $em->flush();
                        return $this->redirectToRoute('showDBBook');
                    }
                    return $this->renderForm('book/editformbook.html.twig', [
                        'fr' =>$form
                    ]);
                }

                 //remove un book
                 #[Route('/RemoveDBbook/{ref}', name: 'RemoveDBbook')]
                 public function RemoveDBbook($ref,BookRepository $BookRepository,ManagerRegistry $ManagerRegistry): Response
                 {
                     $em = $ManagerRegistry->getManager();
                     $dataid = $BookRepository->find($ref);
                     $form = $this->createForm(BookType::class, $dataid);
                         $em->remove($dataid);
                         $em->flush();
                         return $this->redirectToRoute('showDBBook');

                 }


                  //afficher les details d'un book
                  #[Route('/Detailsbook/{ref}', name: 'Detailsbook')]
                  public function Detailsbook($ref,BookRepository $BookRepository): Response
                  {
                      $book = $BookRepository->find($ref);
                     
                      return $this->renderForm('book/Detailsbook.html.twig', [
                        'book' => $book
                    ]);
 
                  }
}
