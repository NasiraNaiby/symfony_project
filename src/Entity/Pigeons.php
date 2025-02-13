<?php

namespace App\Entity;

use App\Repository\PigeonsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PigeonsRepository::class)]
class Pigeons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $img_src = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Animal")
     * @ORM\JoinColumn(name="type_name", referencedColumnName="id", nullable=false)
     */
    private $type_name;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function getImgSrc(): ?string
    {
        return $this->img_src;
    }

    public function setImgSrc(string $img_src): static
    {
        $this->img_src = $img_src;
        return $this;
    }

    public function getTypeName(): ?Animal
    {
        return $this->type_name;
    }

    public function setTypeName(Animal $type_name): static
    {
        $this->type_name = $type_name;
        return $this;
    }
}
