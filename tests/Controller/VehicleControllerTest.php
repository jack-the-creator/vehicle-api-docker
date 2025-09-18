<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Vehicle;
use App\Entity\VehicleSpecParameter;
use Doctrine\ORM\EntityManagerInterface;

class VehicleControllerTest extends AbstractControllerTestCase
{
    private EntityManagerInterface $manager;
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->manager = $container->get(EntityManagerInterface::class);
        $this->vehicle = $this->manager->getRepository(Vehicle::class)->findOneBy(['name' => 'Mustang GT']);
    }

    public function testUpdateVehicleSpecWithInvalidUser(): void
    {
        $token = $this->getAuthToken();

        $this->makeUpdateVehicleSpecRequest($token, 1, '150');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateVehicleSpecWithValidUserAndValue(): void
    {
        $token = $this->getAuthToken('admin', 'adminpass');
        $value = '150';

        $this->makeUpdateVehicleSpecRequest($token, $this->vehicle->getId(), $value);

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertSame($value, $this->vehicle->getVehicleSpecs()->first()->getValue());
    }

    public function testUpdateVehicleSpecWithValidUserAndInvalidValue(): void
    {
        $token = $this->getAuthToken('admin', 'adminpass');
        $value = 'test';

        $this->makeUpdateVehicleSpecRequest($token, $this->vehicle->getId(), $value);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    private function makeUpdateVehicleSpecRequest(
        string $token,
        int $vehicleId,
        string $value,
        string $specParameterName = VehicleSpecParameter::NAME_TOP_SPEED
    ): void {
        $this->client->request('PATCH', "/api/vehicle/$vehicleId/specs/$specParameterName", ['value' => $value], [], [
            'HTTP_Authorization' => "Bearer $token"
        ]);
    }
}
