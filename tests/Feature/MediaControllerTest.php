<?php

namespace Tests\Feature;

use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchMedia()
    {
        $this->withoutExceptionHandling();

        // Create test media
        $media1 = Media::factory()->create(['title' => 'Test Media 1', 'poster' => 'url-link']);
        $media2 = Media::factory()->create(['title' => 'Test Media 2', 'poster' => 'url-link']);

        $query = 'Test Media';

        $response = $this->get('/files?query=' . urlencode($query));

        $response->assertOk();

        $response->assertJsonFragment(['title' => $media1->title]);
        $response->assertJsonFragment(['title' => $media2->title]);
    }
}
