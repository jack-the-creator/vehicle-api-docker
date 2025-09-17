<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use App\Entity\VehicleMake;
use App\Entity\VehicleSpec;
use App\Entity\VehicleSpecParameter;
use App\Entity\VehicleType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VehicleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Fetch makes and types
        $vehicleMakeRepo = $manager->getRepository(VehicleMake::class);
        $ford = $vehicleMakeRepo->findOneBy(['name' => VehicleMake::FORD]);
        $honda = $vehicleMakeRepo->findOneBy(['name' => VehicleMake::HONDA]);
        $toyota = $vehicleMakeRepo->findOneBy(['name' => VehicleMake::TOYOTA]);
        $volkswagen = $vehicleMakeRepo->findOneBy(['name' => VehicleMake::VOLKSWAGEN]);
        $bmw = $vehicleMakeRepo->findOneBy(['name' => VehicleMake::BMW]);

        $carType = $manager->getRepository(VehicleType::class)->findOneBy(['name' => VehicleType::TYPE_CAR]);
        $motorbikeType = $manager->getRepository(VehicleType::class)->findOneBy(['name' => VehicleType::TYPE_MOTORBIKE]);
        $truckType = $manager->getRepository(VehicleType::class)->findOneBy(['name' => VehicleType::TYPE_TRUCK]);

        $specRepo = $manager->getRepository(VehicleSpecParameter::class);

        // Example vehicles
        $vehiclesData = [
            // Cars
            [
                'name' => 'Mustang GT',
                'year' => 2020,
                'make' => $ford,
                'type' => $carType,
                'specs' => [
                    'top_speed' => '155',
                    'horsepower' => '450',
                    'torque' => '420',
                    'engine_capacity' => '5000',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Manual',
                    'weight' => '1650',
                    'length' => '4784',
                    'width' => '1916',
                    'height' => '1381',
                ]
            ],
            [
                'name' => 'Civic Type R',
                'year' => 2022,
                'make' => $honda,
                'type' => $carType,
                'specs' => [
                    'top_speed' => '169',
                    'horsepower' => '306',
                    'torque' => '295',
                    'engine_capacity' => '2000',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Manual',
                    'weight' => '1380',
                    'length' => '4557',
                    'width' => '1877',
                    'height' => '1416',
                ]
            ],
            [
                'name' => 'Corolla',
                'year' => 2021,
                'make' => $toyota,
                'type' => $carType,
                'specs' => [
                    'top_speed' => '112',
                    'horsepower' => '139',
                    'torque' => '171',
                    'engine_capacity' => '1800',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Automatic',
                    'weight' => '1315',
                    'length' => '4630',
                    'width' => '1780',
                    'height' => '1435',
                ]
            ],
            // Motorbikes
            [
                'name' => 'CBR600RR',
                'year' => 2022,
                'make' => $honda,
                'type' => $motorbikeType,
                'specs' => [
                    'top_speed' => '155',
                    'horsepower' => '113',
                    'torque' => '48',
                    'engine_capacity' => '599',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Manual',
                    'weight' => '194',
                    'length' => '2030',
                    'width' => '685',
                    'height' => '1150',
                ]
            ],
            [
                'name' => 'R 1250 GS',
                'year' => 2023,
                'make' => $bmw,
                'type' => $motorbikeType,
                'specs' => [
                    'top_speed' => '125',
                    'horsepower' => '136',
                    'torque' => '143',
                    'engine_capacity' => '1254',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Manual',
                    'weight' => '249',
                    'length' => '2207',
                    'width' => '952',
                    'height' => '1430',
                ]
            ],
            [
                'name' => 'Volkswagen StreetX',
                'year' => 2023,
                'make' => $volkswagen,
                'type' => $motorbikeType,
                'specs' => [
                    'top_speed' => '130',
                    'horsepower' => '98',
                    'torque' => '105',
                    'engine_capacity' => '850',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Manual',
                    'weight' => '210',
                    'length' => '2100',
                    'width' => '800',
                    'height' => '1200',
                ]
            ],
            // Trucks
            [
                'name' => 'F-150',
                'year' => 2023,
                'make' => $ford,
                'type' => $truckType,
                'specs' => [
                    'top_speed' => '107',
                    'horsepower' => '400',
                    'torque' => '500',
                    'engine_capacity' => '3496',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Automatic',
                    'weight' => '2135',
                    'length' => '5890',
                    'width' => '2029',
                    'height' => '1961',
                ]
            ],
        ];

        foreach ($vehiclesData as $data) {
            $vehicle = new Vehicle();
            $vehicle->setName($data['name']);
            $vehicle->setYear($data['year']);
            $vehicle->setMake($data['make']);
            $vehicle->setType($data['type']);

            foreach ($data['specs'] as $paramName => $value) {
                $parameter = $specRepo->findOneBy(['name' => $paramName]);
                if ($parameter) {
                    $spec = new VehicleSpec();
                    $spec->setVehicle($vehicle);
                    $spec->setSpecParameter($parameter);
                    $spec->setValue($value);
                    $vehicle->getVehicleSpecs()->add($spec);
                    $manager->persist($spec);
                }
            }

            $manager->persist($vehicle);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VehicleMakeFixtures::class,
            VehicleTypeFixtures::class,
            VehicleSpecParameterFixtures::class,
        ];
    }
}
