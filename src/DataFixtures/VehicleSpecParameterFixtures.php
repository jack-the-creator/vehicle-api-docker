<?php

namespace App\DataFixtures;

use App\Entity\VehicleSpecParameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehicleSpecParameterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $parameters = [
            ['name' => VehicleSpecParameter::NAME_TOP_SPEED, 'unit' => 'mph', 'datatype' => 'int'],
            ['name' => VehicleSpecParameter::NAME_HORSEPOWER, 'unit' => 'hp', 'datatype' => 'int'],
            ['name' => VehicleSpecParameter::NAME_TORQUE, 'unit' => 'Nm', 'datatype' => 'int'],
            ['name' => VehicleSpecParameter::NAME_ENGINE_CAPACITY, 'unit' => 'cc', 'datatype' => 'int'],
            ['name' => VehicleSpecParameter::NAME_FUEL_TYPE, 'unit' => null, 'datatype' => 'string'],
            ['name' => VehicleSpecParameter::NAME_TRANSMISSION, 'unit' => null, 'datatype' => 'string'],
            ['name' => VehicleSpecParameter::NAME_WEIGHT, 'unit' => 'kg', 'datatype' => 'int'],
            ['name' => VehicleSpecParameter::NAME_LENGTH, 'unit' => 'mm', 'datatype' => 'int'],
            ['name' => VehicleSpecParameter::NAME_WIDTH, 'unit' => 'mm', 'datatype' => 'int'],
            ['name' => VehicleSpecParameter::NAME_HEIGHT, 'unit' => 'mm', 'datatype' => 'int'],
        ];

        foreach ($parameters as $param) {
            $vehicleSpecParameter = new VehicleSpecParameter();
            $vehicleSpecParameter->setName($param['name']);
            $vehicleSpecParameter->setUnit($param['unit']);
            $vehicleSpecParameter->setDatatype($param['datatype']);
            $manager->persist($vehicleSpecParameter);
        }

        $manager->flush();
    }
}
