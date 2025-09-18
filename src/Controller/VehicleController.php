<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Vehicle;
use App\Service\VehicleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
final class VehicleController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly VehicleService $vehicleService
    ) {}

    #[Route('/vehicle/{id}', name: 'vehicle_details', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getVehicleDetails(int $id): JsonResponse
    {
        $vehicle = $this->vehicleService->getVehicle($id);

        return $this->json($vehicle, context: ['groups' => ['vehicle:read', 'vehicle-spec:read']]);
    }

    #[Route('/vehicle/{id}/specs/{specParameterName}', name: 'vehicle_update_spec', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateVehicleSpec(
        int            $id,
        string         $specParameterName,
        Request        $request,
    ): JsonResponse {
        $value = $request->getPayload()->get('value');
        if ($value === null || trim($value) === '') {
            return $this->json(['error' => 'Value is required'], 400);
        }

        $vehicleSpec = $this->vehicleService->updateSpec($id, $specParameterName, $value);

        return $this->json([
            'id' => $vehicleSpec->getVehicle()->getId(),
            'vehicle' => $vehicleSpec->getVehicle()->getName(),
            'parameter' => $vehicleSpec->getSpecParameter()->getName(),
            'value' => $vehicleSpec->getValue(),
            'unit' => $vehicleSpec->getSpecParameter()->getUnit(),
        ]);
    }

    #[Route('/vehicles', name: 'all_vehicles', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getAllVehicles(): JsonResponse
    {
        $vehicles = $this->manager->getRepository(Vehicle::class)->findAll();

        return $this->json($vehicles, context: ['groups' => ['vehicle:read']]);
    }
}
