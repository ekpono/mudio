<?php

namespace Database\Factories;

use App\Enums\MediaVisibility;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'file_name' => $this->faker->word,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'uploaded_from_ip' => $this->faker->ipv4,
            'file_type' => $this->faker->randomElement(['audio', 'video']),
            'path' => $this->faker->word,
            'extension' => $this->faker->fileExtension(),
            'poster' => null,
            'source' => $this->faker->randomElement(['s3', 'local']),
            'visibility' => MediaVisibility::PUBLIC,
            'response' => null,
            'updated_by' => null,
            'deleted_by' => null,
            'state' => 1,
            'country' => 1,
            'continent' => 1,
        ];
    }
}
