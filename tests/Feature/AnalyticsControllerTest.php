<?php

namespace Tests\Feature;

use App\Models\Analytics;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_analytics_for_a_video()
    {
        // Crear un video
        $video = Video::factory()->create();

        // Crear analíticas para el video
        Analytics::factory()->create(['video_id' => $video->id, 'views' => 5, 'searches' => 3]);

        // Hacer una solicitud GET para obtener las analíticas
        $response = $this->getJson("/api/videos/{$video->id}/analytics");

        // Afirmaciones
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'views' => 5,
                         'searches' => 3,
                     ],
                     'message' => 'Analíticas obtenidas exitosamente'
                 ]);
    }

    /** @test */
    public function it_returns_404_if_video_not_found()
    {
        // Hacer una solicitud GET para obtener analíticas de un video que no existe
        $response = $this->getJson("/api/videos/999/analytics");

        // Afirmaciones
        $response->assertStatus(404)
                 ->assertJson(['error' => 'Not Found']);
    }
}
