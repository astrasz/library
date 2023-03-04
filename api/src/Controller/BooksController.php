<?php

namespace App\Controller;

use Exception;
use App\Entity\Book;
use App\Entity\Author;
use App\Service\BooksService;
use App\Controller\BaseController;
use App\Repository\BookRepository;
use App\Entity\DTO\CreateOrUpdateBookDTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/api/books', name: 'api_')]
class BooksController extends BaseController
{

    public function __construct(private BooksService $booksService)
    {
    }

    #[Route(name: 'books', methods: ['GET'])]
    public function cget(BookRepository $bookRepository): JsonResponse
    {
        try {
            $books = $bookRepository->findAll();

            $json = $this->serializeToJson($books, ['book']);

            return $this->createApiResponse(json_decode($json));
        } catch (Exception $e) {
            return $this->createApiResponse($e->getMessage(), $e->getCode());
        }
    }

    #[Route(name: 'add_book', methods: ['POST'])]
    public function addBook(Request $request): JsonResponse
    {
        try {
            $content  = $request->toArray();
            foreach ($content as $key => $param) {
                if ($param === '')
                    $content[$key] = null;
            }


            $addBookDTO = $this->createDTO(json_encode($content), CreateOrUpdateBookDTO::class);

            $book = $this->booksService->createOrUpdate($addBookDTO);
            if (!($book instanceof Book)) {
                return $this->createApiResponse($book, JsonResponse::HTTP_BAD_REQUEST);
            }

            $json = $this->serializeToJson($book, ['book']);

            return $this->createApiResponse($json, JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->createApiResponse(["id" => $e->getMessage()], $e->getCode());
        }
    }


    #[Route('/{id}', name: 'get_book', methods: ['GET'])]
    public function getBook(Book $book = null): JsonResponse
    {
        try {
            if (!($book instanceof Book) || !$book) {
                return $this->createApiResponse(["id" => "Book not found"], JsonResponse::HTTP_NOT_FOUND);
            }
            $json = $this->serializeToJson($book, ['book']);

            return $this->createApiResponse($json);
        } catch (Exception $e) {
            return $this->createApiResponse($e->getMessage(), $e->getCode());
        }
    }

    #[Route('/{id}', name: 'remove_book', methods: ['DELETE'])]
    public function removeBook(Book $book = null, ManagerRegistry $doctrine): JsonResponse
    {
        try {
            if (!($book instanceof Book) || !$book) {
                return $this->createApiResponse(["id" => "Book not found"], JsonResponse::HTTP_NOT_FOUND);
            }

            $id = $book->getId();

            $doctrine->getManager()->remove($book);
            $doctrine->getManager()->flush();

            $message = sprintf('Book with id %d has been successfully removed', $id);

            return $this->createApiResponse($message,);
        } catch (Exception $e) {
            return $this->createApiResponse($e->getMessage(), $e->getCode());
        }
    }

    #[Route('/{id}', name: 'update_book', methods: ['PUT'])]
    public function updateBook(Book $book = null, Request $request): JsonResponse
    {
        try {
            if (!($book instanceof Book) || !$book) {
                return $this->createApiResponse(["id" => "Book not found"], JsonResponse::HTTP_NOT_FOUND);
            }

            $addBookDTO = $this->createDTO($request->getContent(), CreateOrUpdateBookDTO::class);

            $book = $this->booksService->createOrUpdate($addBookDTO, $book);
            if (!($book instanceof Book)) {
                return $this->createApiResponse($book, JsonResponse::HTTP_BAD_REQUEST);
            }

            $json = $this->serializeToJson($book, ['book']);

            return $this->createApiResponse($json);
        } catch (Exception $e) {
            return $this->createApiResponse($e->getMessage(), $e->getCode());
        }
    }

    #[Route('/{bookId}/authors/{authorId}', name: 'add_book_author', methods: ['PATCH'])]
    public function addBookAuthor(Book $book = null, Author $author = null): JsonResponse
    {
        try {

            var_dump($book);
            dd($author);

            return $this->json('');
        } catch (Exception $e) {
        }
    }
}
