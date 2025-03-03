<?php
namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class Helpers{
    private $langue ;
   public function __construct(private LoggerInterface $logger, private Security $security){
    
    }
    public function sayCC():string{
        $this->logger->info(message: 'Bonjour');
        return 'coucou';
    }

    public function getUser():User{
       
        if($this->security->isGranted('ROLE_ADMIN')){
            $user = $this->security->getUser();
            if($user instanceof User){
                return $user;
            }
           
        }
        
        //return $this->security->getUser();
         
    }
}