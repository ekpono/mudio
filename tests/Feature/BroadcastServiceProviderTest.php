<?php

namespace Tests\Feature;

use App\Providers\BroadcastServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class BroadcastServiceProviderTest extends TestCase
{
    use RefreshDatabase;


    public function testBoot()
    {
        $this->artisan('route:clear');

        $this->assertFileExists(base_path('routes/channels.php'));

        $serviceProvider = new BroadcastServiceProvider($this->app);
        $serviceProvider->boot();
    }
}
