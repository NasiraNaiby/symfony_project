<?php

namespace App\Event;

use App\Entity\Personne;
use Symfony\Contracts\EventDispatcher\Event;

//CREATION OF AN EVENT 
class AddPersonneEvent extends Event{
    const ADD_PERSONNE_EVENT  = "person.added";
    public function __construct(private Personne $personne){}

    public function getPersonne(): Personne{
            return $this->personne;
    }
}