<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VehicleSpecRepository;
use App\Validator as AcmeAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehicleSpecRepository::class)]
#[ORM\Table(name: 'vehicle_spec')]
#[AcmeAssert\ValidVehicleSpecValue]
class VehicleSpec
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicle-spec:read', 'vehicle-spec-param:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['vehicle-spec:read', 'vehicle-spec-param:read'])]
    #[Assert\NotBlank]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'vehicleSpecs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vehicle-spec-param:read'])]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToOne(inversedBy: 'vehicleSpecs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vehicle-spec:read', 'vehicle-spec-param:read'])]
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
