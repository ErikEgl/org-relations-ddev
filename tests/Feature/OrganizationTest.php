<?php

namespace Tests\Feature;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase; 

    public function test_can_create_hierarchy_and_get_relations(): void
    {

        $payload = [
            "org_name" => "Banana tree",
            "daughters" => [
                ["org_name" => "Yellow Banana"],
                ["org_name" => "Black Banana"]
            ]
        ];


        $response = $this->postJson('/api/organizations', $payload);
        $response->assertStatus(201);

        $this->assertDatabaseHas('organizations', ['org_name' => 'Black Banana']);


        $response = $this->getJson('/api/organizations/Black Banana/relations');

        $response->assertStatus(200)
            ->assertJsonFragment([
                "relationship_type" => "parent",
                "org_name" => "Banana tree"
            ])
            ->assertJsonFragment([
                "relationship_type" => "sister",
                "org_name" => "Yellow Banana"
            ]);
    }
}