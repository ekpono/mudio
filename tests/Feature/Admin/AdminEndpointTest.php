<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\Role;

class AdminEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminRouteAccess()
    {
        $this->withExceptionHandling();

        $adminUser = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $this->actingAs($adminUser);

        $adminRoute = route('admin.dashboard'); // Adjust this based on your route names

        $response = $this->get($adminRoute);

        // Assert that the response is successful (status 200)
        $response->assertStatus(200);

        // Create a user with a different role (e.g., "user")
        $regularUser = User::factory()->create([
            'role' => Role::USER,
        ]);


        $this->actingAs($regularUser);

        $response = $this->get($adminRoute);

        $response->assertStatus(403);
    }
}
