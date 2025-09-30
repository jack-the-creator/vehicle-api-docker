<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\VehicleMake;
use App\Entity\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
final class VehicleMakeController extends AbstractController
{
    #[Route('/make', name: 'vehicle_make_by_type', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getMakesByVehicleType(Request $request, EntityManagerInterface $manager): JsonResponse
    {
        $typeName = $request->query->get('type');
        if ($typeName === null || trim($typeName) === '') {
            return $this->json(['error' => 'Vehicle type is required'], Response::HTTP_BAD_REQUEST);
        }

        $type = $manager->getRepository(VehicleType::class)->findOneByName($typeName);
        if (!$type instanceof VehicleType) {
            return $this->json(['error' => sprintf('Vehicle type "%s" not found', $typeName)], Response::HTTP_NOT_FOUND);
        }

        $makes = $manager->getRepository(VehicleMake::class)->findByVehicleType($type);

        return $this->json($makes, context: ['groups' => ['vehicle-make:read']]);
    }
}
