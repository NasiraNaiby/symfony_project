<?php
namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class Helpers{
    private $langue ;
    private $security;
   public function __construct(private LoggerInterface $logger, Security $security){
    
    }
    public function sayCC():string{
        $this->logger->info(message: 'Bonjour');
        return 'coucou';
    }

    public function getUser():User{
        return $this->security->getUser();
        //return $this->security->getUser();
         
    }
}