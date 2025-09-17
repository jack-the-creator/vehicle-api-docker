<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VehicleSpecRepository;
use App\Validator as AcmeAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleSpecRepository::class)]
#[ORM\Table(name: 'vehicle_spec')]
#[AcmeAssert\ValidVehicleSpecValue]
class VehicleSpec
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicle-spec:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicle-spec:read'])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'vehicleSpecs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToOne(inversedBy: 'vehicleSpecs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vehicle-spec:read'])]
    private ?VehicleSpecParameter $specParameter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getSpecParameter(): ?VehicleSpecParameter
    {
        return $this->specParameter;
    }

    public function setSpecParameter(?VehicleSpecParameter $specParameter): static
    {
        $this->specParameter = $specParameter;

        return $this;
    }
}
