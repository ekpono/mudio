<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statesData = json_decode(file_get_contents(database_path('seeders/data/states.json')));

        foreach ($statesData as $stateData) {
            $country = DB::table('countries')
                ->where('name', 'LIKE', "%$stateData->country_name%")
                ->first();

            if ($country) {
                DB::table('states')->insert([
                    'name' => $stateData->name,
                    'country_id' => $country->id,
                ]);
            }

        }
    }
}
