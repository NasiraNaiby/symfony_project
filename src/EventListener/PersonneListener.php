<?php
// src/EventListener/PersonneListener.php
namespace App\EventListener;

use App\Event\AddPersonneEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
    public function __construct(private LoggerInterface $logger)
    {
        
    }
    public function onPersonneAdd(AddPersonneEvent $event)
    {
        $this->logger->debug("the personne is added ".$event->getPersonne()->getName());
    }

    
}
