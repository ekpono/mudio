<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingsFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'key' => 'preferred_location',
            'value' => 1 // Preferred country id
        ];
    }
}
