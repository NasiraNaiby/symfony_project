<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $baseProfiles = [
            ['rs' => 'Facebook', 'url' => 'https://www.facebook.com/wyz'],
            ['rs' => 'Twitter', 'url' => 'https://www.twitter.com/wyz'],
            ['rs' => 'LinkedIn', 'url' => 'https://www.linkedin.com/vsdvsdv'],
            ['rs' => 'GitHub', 'url' => 'https://www.github.com/wyz'],
        ];
        
        $uniqueIdentifier = 0;
        for ($i = 0; $i < 25; $i++) {
            foreach ($baseProfiles as $profileData) {
                $profile = new Profile();
                $profile->setRs($profileData['rs']);
                $profile->setUrl($profileData['url'] . $uniqueIdentifier);  // Append unique identifier to the URL
                $manager->persist($profile);
                $uniqueIdentifier++;
            }
        }
        
        $manager->flush();
        
            

    }
}
