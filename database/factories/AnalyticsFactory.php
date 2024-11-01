<?php

namespace Database\Factories;

use App\Models\Analytics;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyticsFactory extends Factory
{
    protected $model = Analytics::class;

    public function definition()
    {
        return [
            'video_id' => 1, // Ajusta esto según sea necesario
            'views' => $this->faker->numberBetween(0, 100),
            'searches' => $this->faker->numberBetween(0, 50),
        ];
    }
}