<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ORM\Table(name: 'vehicle')]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicle:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicle:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['vehicle:read'])]
    private ?int $year = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vehicle:read'])]
    private ?VehicleMake $make = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vehicle:read'])]
    private ?VehicleType $type = null;

    /**
     * @var Collection<int, VehicleSpec>
     */
    #[ORM\OneToMany(targetEntity: VehicleSpec::class, mappedBy: 'vehicle', orphanRemoval: true)]
    #[Groups(['vehicle-spec:read'])]
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getMake(): ?VehicleMake
    {
        return $this->make;
    }

    public function setMake(?VehicleMake $make): static
    {
        $this->make = $make;

        return $this;
    }

    public function getType(): ?VehicleType
    {
        return $this->type;
    }

    public function setType(?VehicleType $type): static
    {
        $this->type = $type;

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
            $vehicleSpec->setVehicle($this);
        }

        return $this;
    }

    public function removeVehicleSpec(VehicleSpec $vehicleSpec): static
    {
        if ($this->vehicleSpecs->removeElement($vehicleSpec)) {
            // set the owning side to null (unless already changed)
            if ($vehicleSpec->getVehicle() === $this) {
                $vehicleSpec->setVehicle(null);
            }
        }

        return $this;
    }
}
