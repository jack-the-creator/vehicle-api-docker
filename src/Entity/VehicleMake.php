<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VehicleMakeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleMakeRepository::class)]
#[ORM\Table(name: 'vehicle_make')]
class VehicleMake
{
    public const string FORD = 'ford';
    public const string HONDA = 'honda';
    public const string TOYOTA = 'toyota';
    public const string VOLKSWAGEN = 'volkswagen';
    public const string BMW = 'bmw';

    public const array MAKES = [
        self::FORD,
        self::HONDA,
        self::TOYOTA,
        self::VOLKSWAGEN,
        self::BMW,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicle-make:read', 'vehicle:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicle-make:read', 'vehicle:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Vehicle>
     */
    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'make')]
    private Collection $vehicles;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
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

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setMake($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getMake() === $this) {
                $vehicle->setMake(null);
            }
        }

        return $this;
    }
}
