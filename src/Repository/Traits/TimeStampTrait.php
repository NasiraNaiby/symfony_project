<?php

namespace App\Repository\Traits;
use Doctrine\ORM\Mapping as ORM;

trait TimeStampTrait{

    

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $createdAt = null;


    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updateAt = null;

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTime
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTime $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }
    #[ORM\PrePersist()]
    public function onPrePersist(){
        $this->createdAt = new \DateTime();
        $this->updateAt = new \DateTime();
    }

    #[ORM\PreUpdate()]
    public function onPreUpdate(){
        $this->updateAt = new \DateTime();
    }
}