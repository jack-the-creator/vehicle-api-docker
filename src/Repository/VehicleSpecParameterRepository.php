<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\VehicleSpecParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleSpecParameter>
 */
class VehicleSpecParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleSpecParameter::class);
    }

    public function findOneByName(string $name): ?VehicleSpecParameter
    {
        return $this->createQueryBuilder('vsp')
            ->andWhere('vsp.name = :name')
            ->setParameter('name', strtolower($name))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
