<?php

namespace App\Entity;

use App\Repository\HobbyRepository;
use App\Repository\Traits\TimeStampTrait;  
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HobbyRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Hobby
{
    use TimeStampTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $designations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignations(): ?string
    {
        return $this->designations;
    }

    public function setDesignations(string $designations): static
    {
        $this->designations = $designations;

        return $this;
    }
    public function __toString()
    {
        return $this->designations;
    }
}
