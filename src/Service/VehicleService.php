<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\VehicleSpec;
use App\Entity\VehicleSpecParameter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class VehicleService
{
    public function __construct(
        private EntityManagerInterface $manager,
        private ValidatorInterface     $validator,
    ) {}

    public function updateSpec(int $vehicleId, string $specParameterName, string $value): VehicleSpec
    {
        $vehicle = $this->getVehicle($vehicleId);
        $specParameter = $this->getVehicleSpecParameter($specParameterName);
        $vehicleSpec = $this->getVehicleSpec($vehicle, $specParameter);

        $vehicleSpec->setValue($value);
        $this->validateVehicleSpecValue($vehicleSpec);
        $this->manager->flush();

        return $vehicleSpec;
    }

    public function getVehicle(int $vehicleId): Vehicle
    {
        $vehicle = $this->manager->getRepository(Vehicle::class)->find($vehicleId);
        if (!$vehicle) {
            throw new NotFoundHttpException(sprintf('Vehicle with Id "%s" not found', $vehicleId));
        }

        return $vehicle;
    }

    private function getVehicleSpecParameter(string $specParameterName): VehicleSpecParameter
    {
        $specParameter = $this->manager->getRepository(VehicleSpecParameter::class)->findOneBy(['name' => strtolower($specParameterName)]);
        if (!$specParameter) {
            throw new NotFoundHttpException(sprintf('Spec parameter "%s" not found', $specParameterName));
        }

        return $specParameter;
    }

    private function getVehicleSpec(Vehicle $vehicle, VehicleSpecParameter $specParameter): VehicleSpec
    {
        $vehicleSpec = $this->manager->getRepository(VehicleSpec::class)
            ->findOneBy(['vehicle' => $vehicle, 'specParameter' => $specParameter]);

        if (!$vehicleSpec) {
            throw new \RuntimeException(sprintf(
                'Spec "%s" not set for vehicle "%s"',
                $specParameter->getName(),
                $vehicle->getName()
            ));
        }

        return $vehicleSpec;
    }

    private function validateVehicleSpecValue(VehicleSpec $vehicleSpec): void
    {
        $errors = $this->validator->validate($vehicleSpec);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }
    }
}
