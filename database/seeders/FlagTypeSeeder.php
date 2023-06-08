<?php

namespace Database\Seeders;

use App\Models\FlagType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlagTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (FlagType::count() > 0) return;

        $flagTypes = [
            'Sexual content',
            'Violent or repulsive content',
            'Hateful or abusive content',
            'Harassment or bullying',
            'Harmful or dangerous acts',
            'Misinformation',
            'Child abuse',
            'Promotes terrorism',
            'Spam or misleading',
            'Infringes my rights',
            'Captions issue',
        ];

        foreach ($flagTypes as $flagType) {
            FlagType::create(['name' => $flagType]);
        }
    }
}
