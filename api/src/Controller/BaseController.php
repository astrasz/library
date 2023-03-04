<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
    private int $statusCode = 200;

    /**
     * @var SerializerInterface|null
     */
    private $serializer;

    /**
     * @required
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    protected function serializeToJson(Book|Author|array $data, array $groups): string
    {
        if ($this->serializer) {
            return $this->serializer->serialize($data, 'json', ['groups' => $groups]);
        }
    }

    protected function createDTO(string $content, $className)
    {
        if ($this->serializer) {
            return $this->serializer->deserialize($content, $className, 'json');
        }
    }

    protected function createApiResponse(string|array $message = '', ?int $statusCode = null, array $headers = []): JsonResponse
    {
        if ($statusCode / 100 === 2) {
            return $this->returnSuccessResponse($message, $statusCode, $headers);
        }

        if ($statusCode / 100 === 4 || $statusCode / 100 === 5) {
            return $this->returnFailureResponse($message, $statusCode, $headers);
        }

        if (!$statusCode) {
            $statusCode = $this->statusCode;
        }

        return new JsonResponse(['status' => $statusCode, 'message' => $message], $statusCode, $headers);
    }

    private function returnSuccessResponse(string|array $message = '', ?int $statusCode = null, array $headers = []): JsonResponse
    {
        if ($statusCode) {
            $this->setStatusCode($statusCode);
        }

        $data = [
            'status' => $this->getStatusCode(),
            'success' => 'true'
        ];

        if ($message) {
            $data['message'] = $message;
        }

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    private function returnFailureResponse(string | array $errors, int $statusCode, array $headers = []): JsonResponse
    {
        $this->setStatusCode($statusCode);
        $data = [
            'status' => $this->getStatusCode(),
            'success' => 'false',
            'errors' => $errors
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    private function getStatusCode(): int
    {
        return $this->statusCode;
    }

    private function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
