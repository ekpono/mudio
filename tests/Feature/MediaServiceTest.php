<?php

use App\Models\Country;
use App\Models\Media;
use App\Models\Settings;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Torann\GeoIP\Facades\GeoIP;

class MediaServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testComputeMostViewed()
    {
        Media::factory(10)->create();

        $mostViewed = MediaService::computeMostViewed();

        $this->assertInstanceOf(ResourceCollection::class, $mostViewed);
    }

    public function testGetMediaQuery()
    {
        $this->seed();

        $user = User::factory()->create();
        Auth::login($user);

        $matchingStateMedia = Media::factory()->create(['state' => 'Lagos']);
        $matchingCountryMedia = Media::factory()->create(['country' => 'Nigeria']);
        $matchingContinentMedia = Media::factory()->create(['continent' => 'African']);

        $preferredCountry = Country::first();

        $preferredLocation = Settings::factory()->create([
            'user_id' => $user->id,
            'key' => 'preferred_location',
            'value' => $preferredCountry->id,
        ]);

        $userLocation = [
            'state' => 'Some State',
            'country' => 'Some Country',
            'continent' => 'Some Continent',
        ];
        GeoIP::shouldReceive('getLocation')->andReturn($userLocation);

        $mediaQuery = MediaService::getMediaQuery('query');

        $this->assertNotEquals(
            [$matchingStateMedia->id, $matchingCountryMedia->id, $matchingContinentMedia->id],
            $mediaQuery->pluck('id')->toArray()
        );
    }

    public function testMediaResponseIsOrderedBaseOnUserPreference()
    {
        $this->withoutExceptionHandling();

        $this->seed();

        $media = Media::factory(4)->create(['poster' => 'url-link']);

        $user = User::factory()->create();
        Auth::login($user);

        Settings::factory()->create([
            'user_id' => $user->id,
            'key' => 'preferred_location',
            'value' => 1,
        ]);

        $response = $this->get('/files');

        $this->assertEquals(
            $media->pluck('id')->toArray(),
            collect($response->json('media'))->pluck('id')->toArray()
        );
    }

    public function testGetMediaQueryWithCurrentLocation()
    {
        $userLocation = [
            'state' => 'California',
            'country' => 'United States',
            'continent' => 'North America',
        ];
        GeoIP::shouldReceive('getLocation')->andReturn($userLocation);

        $matchingStateMedia = Media::factory(['state' => $userLocation['state']])->create();
        $matchingCountryMedia = Media::factory(['country' => $userLocation['country']])->create();
        $matchingContinentMedia = Media::factory(['continent' => $userLocation['continent']])->create();

        $mediaQuery = MediaService::getMediaQuery('query');

        $this->assertNotEquals(
            [$matchingStateMedia->id, $matchingCountryMedia->id, $matchingContinentMedia->id],
            $mediaQuery->pluck('id')->toArray()
        );
    }
}
