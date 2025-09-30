<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Vehicle;
use App\Entity\VehicleSpecParameter;
use App\Service\VehicleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VehicleServiceTest extends KernelTestCase
{
    private VehicleService $service;
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->manager = $container->get(EntityManagerInterface::class);
        $this->validator = $container->get(ValidatorInterface::class);
        $this->service = new VehicleService($this->manager, $this->validator);
        $this->vehicle = $this->manager->getRepository(Vehicle::class)->findOneBy(['name' => 'Mustang GT']);
    }

    public function testUpdateSpecSuccess(): void
    {
        $updatedSpec = $this->service->updateSpec(
            $this->vehicle->getId(),
            VehicleSpecParameter::NAME_TOP_SPEED,
            '160'
        );

        $this->assertEquals('160', $updatedSpec->getValue());
    }

    public function testUpdateSpecInvalidValue(): void
    {
        $datatype = 'int';
        $parameterName = VehicleSpecParameter::NAME_TOP_SPEED;
        $invalidValue = 'fast';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'This value "%s" is not valid for parameter "%s". Value must be a %s.',
            $invalidValue,
            $parameterName,
            $datatype
        ));

        $this->service->updateSpec($this->vehicle->getId(), $parameterName, $invalidValue);
    }

    public function testUpdateSpecInvalidParameterName(): void
    {
        $parameterName = 'invalid_parameter';
        $value = '160';

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf(
            'Spec parameter "%s" not found',
            $parameterName
        ));

        $this->service->updateSpec($this->vehicle->getId(), $parameterName, $value);
    }

    public function testUpdateSpecInvalidVehicleId(): void
    {
        $vehicleId = 9999;
        $value = '160';
        $parameterName = 'invalid_parameter';

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf(
            'Vehicle with Id "%s" not found',
            $vehicleId
        ));

        $this->service->updateSpec($vehicleId, $parameterName, $value);
    }

    public function testGetVehicleInvalidVehicleId(): void
    {
        $vehicleId = 9999;

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf(
            'Vehicle with Id "%s" not found',
            $vehicleId
        ));

        $this->service->getVehicle($vehicleId);
    }
}
