<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowMedia()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $media = Media::factory()->create(['poster' => 'poster']);

        $ipAddress = '127.0.0.1';

        $this->actingAs($user)
           ->get("/watch/{$media->id}");

        $this->assertDatabaseCount('media_views', 1);


        $this->assertDatabaseHas('media_views', [
            'media_id' => $media->id,
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
            'is_auth' => 0,
            'visits' => 1,
        ]);
    }
}
