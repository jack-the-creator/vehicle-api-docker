<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\VehicleMake;
use App\Entity\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        if (!$typeName) {
            return $this->json(['error' => 'Vehicle type is required'], 400);
        }

        $type = $manager->getRepository(VehicleType::class)->findOneBy(['name' => strtolower($typeName)]);
        if (!$type) {
            return $this->json(['error' => sprintf('Vehicle type "%s" not found', $typeName)], 404);
        }

        $makes = $manager->getRepository(VehicleMake::class)->findByVehicleType($type);

//        $makeDTOs = array_map(fn($make) => new VehicleMakeDTO(
//            $make->getId(),
//            $make->getName(),
//            $make->getCountry()
//        ), $makes);

        return $this->json($makes, context: ['groups' => ['vehicle-make:read']]);
    }
}
