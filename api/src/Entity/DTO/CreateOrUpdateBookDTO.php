<?php

namespace App\Entity\DTO;


use Symfony\Component\Validator\Constraints as Assert;


class CreateOrUpdateBookDTO extends AbstractDTO
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $title;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $publisher;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    private int $pages;

    private ?bool $published = false;

    private ?array $authors = [];


    /**
     * @return string
     */

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * @return int
     */

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }
    /**
     * @return boolean
     */

    public function getPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    /**
     * @return array
     */

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }
}
