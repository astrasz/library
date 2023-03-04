<?php

namespace App\Entity\DTO;


use Symfony\Component\Validator\Constraints as Assert;


class CreateOrUpdateAuthorDTO extends AbstractDTO
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $name;

    private ?string $originCountry = null;


    /**
     * @return string
     */

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */

    public function getOriginCountry(): string
    {
        return $this->originCountry;
    }

    public function setOriginCountry(string $originCountry): void
    {
        $this->originCountry = $originCountry;
    }
}
