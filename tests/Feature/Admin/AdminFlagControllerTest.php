<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Flag;
use Illuminate\Http\Response;

class AdminFlagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFlagIndex()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($user);

        $media = Media::factory()->create(['poster' => 'poster']);

        $flagCount = 5;
        Flag::factory($flagCount)->create([
            'media_id' => $media
        ]);

        $flagIndexRoute = route('admin.report.index');

        $response = $this->get($flagIndexRoute);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user',
                    'media',
                    'flag_type',
                    'created_at',
                ],
            ],
            'links',
            'meta',
        ]);
    }

    public function testFlagPage()
    {
        $user = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($user);

        $flagPageRoute = route('admin.flag.page');

        $response = $this->get($flagPageRoute);

        $response->assertStatus(Response::HTTP_OK);
    }
}
