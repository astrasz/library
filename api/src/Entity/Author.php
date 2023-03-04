<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[Groups(['author'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['author'])]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Groups(['author'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $originCountry = null;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'authors')]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOriginCountry(): ?string
    {
        return $this->originCountry;
    }

    public function setOriginCountry(?string $originCountry): self
    {
        $this->originCountry = $originCountry;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }
}
