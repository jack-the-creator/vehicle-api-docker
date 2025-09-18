<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VehicleSpecParameterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehicleSpecParameterRepository::class)]
#[ORM\Table(name: 'vehicle_spec_parameter')]
class VehicleSpecParameter
{
    public const string NAME_TOP_SPEED = 'top_speed';
    public const string NAME_HORSEPOWER = 'horsepower';
    public const string NAME_TORQUE = 'torque';
    public const string NAME_ENGINE_CAPACITY = 'engine_capacity';
    public const string NAME_FUEL_TYPE = 'fuel_type';
    public const string NAME_TRANSMISSION = 'transmission';
    public const string NAME_WEIGHT = 'weight';
    public const string NAME_LENGTH = 'length';
    public const string NAME_WIDTH = 'width';
    public const string NAME_HEIGHT = 'height';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicle-spec:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['vehicle-spec:read'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['vehicle-spec:read'])]
    private ?string $unit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Groups(['vehicle-spec:read'])]
    private ?string $datatype = null;

    /**
     * @var Collection<int, VehicleSpec>
     */
    #[ORM\OneToMany(targetEntity: VehicleSpec::class, mappedBy: 'specParameter', orphanRemoval: true)]
    private Collection $vehicleSpecs;

    public function __construct()
    {
        $this->vehicleSpecs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, VehicleSpec>
     */
    public function getVehicleSpecs(): Collection
    {
        return $this->vehicleSpecs;
    }

    public function addVehicleSpec(VehicleSpec $vehicleSpec): static
    {
        if (!$this->vehicleSpecs->contains($vehicleSpec)) {
            $this->vehicleSpecs->add($vehicleSpec);
            $vehicleSpec->setSpecParameter($this);
        }

        return $this;
    }

    public function removeVehicleSpec(VehicleSpec $vehicleSpec): static
    {
        if ($this->vehicleSpecs->removeElement($vehicleSpec)) {
            // set the owning side to null (unless already changed)
            if ($vehicleSpec->getSpecParameter() === $this) {
                $vehicleSpec->setSpecParameter(null);
            }
        }

        return $this;
    }

    public function getDatatype(): ?string
    {
        return $this->datatype;
    }

    public function setDatatype(string $datatype): static
    {
        $this->datatype = $datatype;

        return $this;
    }
}
