<?php

namespace Database\Seeders;

use App\Models\Continent;
use Illuminate\Database\Seeder;

class ContinentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Continent::count() > 0) return;
        $continents = [
            ['name' => 'Africa'],
            ['name' => 'Antarctica'],
            ['name' => 'Asia'],
            ['name' => 'Europe'],
            ['name' => 'North America'],
            ['name' => 'South America'],
            ['name' => 'Australia'],
        ];

        foreach ($continents as $continent) {
            Continent::create($continent);
        }
    }
}
