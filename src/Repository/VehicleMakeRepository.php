<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\VehicleMake;
use App\Entity\VehicleType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleMake>
 */
class VehicleMakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleMake::class);
    }

    /**
     * @param VehicleType $type
     * @return VehicleMake[]
     */
    public function findByVehicleType(VehicleType $type): array
    {
        return $this->createQueryBuilder('vm')
            ->join('vm.vehicles', 'vmv')
            ->where('vmv.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }
}
