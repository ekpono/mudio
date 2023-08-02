<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Response;

class AdminMediaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testMediaIndex()
    {
        $user = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($user);

        $mediaCount = 5;
        Media::factory($mediaCount)->create();

        $mediaIndexRoute = route('admin.media.index');

        $response = $this->get($mediaIndexRoute);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'file_type',
                    'visibility',
                    'total_views',
                    'location',
                    'user' => [
                        'id',
                        'email',
                        'name',
                        'role',
                        'is_blocked',
                        'image',
                        'total_upload',
                    ],
                    'created_at',
                ],
            ],
            'links',
            'meta',
        ]);

        $response->assertJsonFragment([
            'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ]);
    }
}
