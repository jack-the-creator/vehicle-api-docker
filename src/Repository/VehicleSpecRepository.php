<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\VehicleSpec;
use App\Entity\VehicleSpecParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleSpec>
 */
class VehicleSpecRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleSpec::class);
    }

    public function findOneByVehicleAndSpecParameter(Vehicle $vehicle, VehicleSpecParameter $specParameter): ?VehicleSpec
    {
        return $this->createQueryBuilder('vs')
            ->andWhere('vs.vehicle = :vehicle')
            ->andWhere('vs.specParameter = :specParameter')
            ->setParameter('vehicle', $vehicle)
            ->setParameter('specParameter', $specParameter)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
