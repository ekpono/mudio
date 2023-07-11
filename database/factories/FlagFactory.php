<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use App\Models\FlagType;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlagFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'media_id' => Media::factory(),
            'flag_id' => FlagType::factory(),
            'reason' => $this->faker->paragraph,
        ];
    }
}
