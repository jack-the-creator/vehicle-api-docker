<?php

namespace App\DataFixtures;

use App\Entity\VehicleMake;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehicleMakeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (VehicleMake::MAKES as $makeName) {
            $vehicleMake = new VehicleMake();
            $vehicleMake->setName($makeName);
            $manager->persist($vehicleMake);
        }

        $manager->flush();
    }
}
