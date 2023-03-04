<?php

namespace App\Service;


use App\Entity\Book;
use App\Entity\Author;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use App\Entity\DTO\CreateOrUpdateBookDTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BooksService
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private ValidatorInterface $validator,
        private BookRepository $bookRepository,
        private AuthorRepository $authorRepository
    ) {
    }

    public function createOrUpdate(CreateOrUpdateBookDTO $addBookDTO, Book $book = null): Book|array
    {
        $errors = $this->validator->validate($addBookDTO);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $messages;
        }

        $entityManager = $this->doctrine->getManager();

        if (!$book) {
            $book = new Book();
        }

        $book->setTitle($addBookDTO->getTitle());
        $book->setPublisher($addBookDTO->getPublisher());
        $book->setPages($addBookDTO->getPages());
        if ($addBookDTO->getPublished()) {
            $book->setPublished($addBookDTO->getPublished());
        }

        if (count($addBookDTO->getAuthors()) > 0) {

            if (count($addBookDTO->getAuthors()) > 3) {
                throw new BadRequestException('Cannot add more then 3 authors to the book', 400);
            }

            if (
                count($book->getAuthors()) > 0
                &&
                count($book->getAuthors()) + count($addBookDTO->getAuthors()) > 3
            ) {
                throw new BadRequestException('Cannot add more then 3 authors to the book', 400);
            }

            $authors = $this->authorRepository->findAllByIds($addBookDTO->getAuthors());

            if (!$authors || count($authors) === 0) {
                throw new NotFoundHttpException('Selected authors cannot be found', null, 404);
            }

            foreach ($authors as $author) {
                if (!($author instanceof Author)) {
                    throw new NotFoundHttpException('Selected authors cannot be found', null, 404);
                }

                $book->addAuthor($author);
            }
        }

        $entityManager->persist($book);
        $entityManager->flush();

        return $book;
    }
}
