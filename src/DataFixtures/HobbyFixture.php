<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Reading",
            "Cooking",
            "Gardening",
            "Painting",
            "Drawing",
            "Photography",
            "Playing Musical Instruments",
            "Hiking",
            "Cycling",
            "Running",
            "Swimming",
            "Traveling",
            "Writing",
            "Blogging",
            "Knitting",
            "Woodworking",
            "Fishing",
            "Birdwatching",
            "Playing Chess",
            "Playing Video Games",
            "Scrapbooking",
            "Baking",
            "Dancing",
            "Yoga",
            "Meditation",
            "Pottery",
            "Rock Climbing",
            "Martial Arts",
            "Collecting Stamps",
            "Playing Board Games"
        ];
        
    
    for($i = 0; $i < count($data); $i++){
        $hobbies = new Hobby();
        $hobbies->setDesignations($data[$i]);
        
        $manager->persist($hobbies);
        }     


    $manager->flush();
}
}
