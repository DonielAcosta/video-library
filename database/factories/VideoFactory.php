<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Video;
class VideoFactory extends Factory{
    protected $model = Video::class;

    public function definition(){
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'youtube_url' => $this->faker->url,
            'user_id' => 1, 
        ];
    }
}
