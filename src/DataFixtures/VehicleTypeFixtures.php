<?php

namespace App\DataFixtures;

use App\Entity\VehicleType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehicleTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (VehicleType::TYPES as $typeName) {
            $vehicleType = new VehicleType();
            $vehicleType->setName($typeName);
            $manager->persist($vehicleType);
        }

        $manager->flush();
    }
}
