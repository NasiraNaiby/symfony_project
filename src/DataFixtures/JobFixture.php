<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Software Engineer",
            "Data Scientist",
            "Project Manager",
            "Graphic Designer",
            "Marketing Specialist",
            "Sales Manager",
            "Financial Analyst",
            "Human Resources Manager",
            "Product Manager",
            "Customer Service Representative",
            "Business Analyst",
            "Operations Manager",
            "Mechanical Engineer",
            "Electrical Engineer",
            "Civil Engineer",
            "Architect",
            "Pharmacist",
            "Physician",
            "Nurse",
            "Teacher",
            "Professor",
            "Research Scientist",
            "Accountant",
            "Auditor",
            "Consultant",
            "Web Developer",
            "Mobile App Developer",
            "Database Administrator",
            "Network Administrator",
            "Technical Writer"
        ];
        for($i = 0; $i < count($data); $i++){
            $job = new Job();
            $job->setDesignation($data[$i]);
            
            $manager->persist($job);
            }     


        $manager->flush();
    }
}
