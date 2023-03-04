<?php

namespace App\Service;

use App\Entity\Author;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use App\Entity\DTO\CreateOrUpdateAuthorDTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AuthorsService
{

    public function __construct(
        private ManagerRegistry $doctrine,
        private ValidatorInterface $validator,
        private BookRepository $bookRepository,
        private AuthorRepository $authorRepository
    ) {
    }


    public function create(CreateOrUpdateAuthorDTO $addAuthorDTO, Author $author = null): Author|array
    {
        $errors = $this->validator->validate($addAuthorDTO);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $messages;
        }

        $entityManager = $this->doctrine->getManager();

        $author = new Author();

        $author->setName($addAuthorDTO->getName());

        if ($addAuthorDTO->getOriginCountry()) {
            $author->setOriginCountry($addAuthorDTO->getOriginCountry());
        }

        $entityManager->persist($author);
        $entityManager->flush();

        return $author;
    }
}
