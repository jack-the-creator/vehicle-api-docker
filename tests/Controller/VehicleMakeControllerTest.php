<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\VehicleType;

class VehicleMakeControllerTest extends AbstractControllerTestCase
{
    public function testGetMakesByValidVehicleType(): void
    {
        $token = $this->getAuthToken();

        $this->client->request('GET', '/api/make', ['type' => VehicleType::TYPE_CAR], [], [
            'HTTP_Authorization' => "Bearer $token"
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('name', $data[0]);
    }

    public function testGetMakesByInvalidVehicleType(): void
    {
        $token = $this->getAuthToken();
        $invalidType = 'invalid_type';

        $this->client->request('GET', '/api/make', ['type' => $invalidType], [], [
            'HTTP_Authorization' => "Bearer $token"
        ]);

        $this->assertResponseStatusCodeSame(404);
        $this->assertJson($this->client->getResponse()->getContent());
    }
}
