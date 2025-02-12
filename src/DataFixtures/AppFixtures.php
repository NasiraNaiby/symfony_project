<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 20; $i++){
            $animal = new Animal();
            $animal->setName('animal'.$i);
            $animal->setType('type'.$i);
          //  $animal->setAnimalDiet('')
        }

        $manager->flush();
    }
}
