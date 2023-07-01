<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreateAudioFile()
    {
        $this->seed();

        Storage::fake('local');

        $file = UploadedFile::fake()->create('audio.mp3', 1024);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/file', [
            'file' => $file,
            'title' => 'Audio Title',
            'visibility' => 'public',
            'description' => 'Audio Description',
            'tags' => ['tag1', 'tag2'],
            'comments_enabled' => 'true',
        ]);

        $response->assertStatus(200);

        $response->assertSimilarJson([
            'filename' => $file->getClientOriginalName(),
            'path' => $response->json('path'),
        ]);
    }

    public function testUserCanUpdateAudioFile()
    {
        $this->seed();

        $media = Media::factory()->create(['file_type' => 'audio', 'poster' => 'my-poster']);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/file/'.$media->id, [
            'title' => 'Updated Audio Title',
            'visibility' => 'private',
            'description' => 'Updated Audio Description',
            'tags' => ['tag1', 'tag2'],
            'comments_enabled' => 'false',
        ]);

        $response->assertStatus(200);
        // Assert the updated data
        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'title' => 'Updated Audio Title',
            'visibility' => 'private',
            'description' => 'Updated Audio Description',
            'comments_enabled' => 'false',
        ]);
    }

    public function testUserCanDeleteAudioFile()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/file/'.$media->id);

        $response->assertStatus(202);
        // Assert the soft delete
        $this->assertSoftDeleted('media', ['id' => $media->id]);
    }
}
