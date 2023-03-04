<?php

namespace App\Controller;

use Exception;
use App\Entity\Author;
use App\Service\AuthorsService;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\DTO\CreateOrUpdateAuthorDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('api/authors', name: 'api_')]
class AuthorsController extends BaseController
{

    public function __construct(private AuthorsService $authorsService)
    {
    }

    #[Route(name: 'add_author', methods: ['POST'])]
    public function addAuthor(Request $request): JsonResponse
    {
        try {
            $content  = $request->toArray();
            foreach ($content as $key => $param) {
                if ($param === '')
                    $content[$key] = null;
            }


            $addAuthorDTO = $this->createDTO(json_encode($content), CreateOrUpdateAuthorDTO::class);


            $author = $this->authorsService->create($addAuthorDTO);
            if (!($author instanceof Author)) {
                return $this->createApiResponse($author, JsonResponse::HTTP_BAD_REQUEST);
            }

            $json = $this->serializeToJson($author, ['author']);

            return $this->createApiResponse($json, JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->createApiResponse(["id" => $e->getMessage()], $e->getCode());
        }
    }

    #[Route('/{id}', name: 'remove_author', methods: ['DELETE'])]
    public function removeAuthor(Author $author = null, ManagerRegistry $doctrine): JsonResponse
    {
        try {
            if (!($author instanceof Author) || !$author) {
                return $this->createApiResponse(["id" => "Author not found"], JsonResponse::HTTP_NOT_FOUND);
            }

            $id = $author->getId();

            $doctrine->getManager()->remove($author);
            $doctrine->getManager()->flush();

            $message = sprintf('Author with id %d has been successfully removed', $id);

            return $this->createApiResponse($message,);
        } catch (Exception $e) {
            return $this->createApiResponse($e->getMessage(), $e->getCode());
        }
    }
}
