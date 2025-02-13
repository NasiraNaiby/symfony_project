<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    // #[ORM\Column(length: 20)]
    // private ?string $type = null;

    #[ORM\Column(length: 50)]
    private ?string $animal_diet = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pigeon")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    private $type_id;

    #[ORM\ManyToOne(inversedBy: 'animal_id')]
    private ?Reptiles $reptiles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    // public function getType(): ?string
    // {
    //     return $this->type;
    // }

    // public function setType(string $type): static
    // {
    //     $this->type = $type;
    //     return $this;
    // }

    public function getAnimalDiet(): ?string
    {
        return $this->animal_diet;
    }

    public function setAnimalDiet(string $animal_diet): static
    {
        $this->animal_diet = $animal_diet;
        return $this;
    }

    public function getTypeId(): ?Pigeons
    {
        return $this->type_id;
    }

    public function setTypeId(Pigeons $type_id): static
    {
        $this->type_id = $type_id;
        return $this;
    }

    public function getReptiles(): ?Reptiles
    {
        return $this->reptiles;
    }

    public function setReptiles(?Reptiles $reptiles): static
    {
        $this->reptiles = $reptiles;

        return $this;
    }
}
