<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setRs('Facebook');
        $profile->setUrl('https://www.facebook.com/wyz');
        
        $profile1 = new Profile();
        $profile1->setRs('twitter');
        $profile1->setUrl('https://www.twitter.com/wyz');

        $profile2 = new Profile();
        $profile2->setRs('linkden');
        $profile2->setUrl('https://www.linkden.com/wyz');

        $profile3 = new Profile();
        $profile3->setRs('github');
        $profile3->setUrl('https://www.github.com/wyz');
        
        $manager->persist($profile);
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);
        $manager->flush();
            

    }
}
