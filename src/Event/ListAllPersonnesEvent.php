<?php

namespace App\Event;

use App\Entity\Personne;
use Symfony\Contracts\EventDispatcher\Event;

class ListAllPersonnesEvent extends Event{
    const LIST_ALL_PERSONNE_EVENT  = "person.list_all";
    public function __construct(private int $nbPersonne){}

    public function getPersonne(): int {
            return $this->nbPersonne;
    }
}