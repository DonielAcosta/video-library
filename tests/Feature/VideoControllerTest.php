<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Analytics;



class VideoControllerTest extends TestCase{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example(){
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function index_returns_all_videos()
    {
        // Crear algunos videos
        $videos = Video::factory()->count(5)->create();

        // Hacer la solicitud GET para obtener todos los videos
        $response = $this->getJson('/api/videos');

        // Afirmaciones
        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data') // Asegura que la respuesta contenga 5 videos
                 ->assertJsonFragment(['title' => $videos[0]->title]); // Verifica que al menos un título esté presente
    }

    public function creates_a_video_successfully(){
        // Crea un usuario para asociar el video
        $user = User::factory()->create();

        $data = [
            'title' => 'Sample Video',
            'description' => 'This is a sample video description.',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/videos', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'created' => true,
                     'message' => 'Video creado exitosamente',
                 ]);

        // Verificar que el video fue creado en la base de datos
        $this->assertDatabaseHas('videos', [
            'title' => 'Sample Video',
            'user_id' => $user->id,
        ]);
    }

    public function returns_validation_errors_if_data_is_invalid() {
        // Datos inválidos (falta el título)
        $data = [
            'description' => 'This is a sample video description.',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'user_id' => 1, // Suponiendo que no existe un usuario con ID 1
        ];

        $response = $this->postJson('/api/videos', $data);

        $response->assertStatus(422)->assertJsonStructure(['error' => ['title']]);
    }

    public function returns_validation_error_if_user_does_not_exist(){
        // Datos válidos, pero el user_id no existe
        $data = [
            'title' => 'Sample Video',
            'description' => 'This is a sample video description.',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'user_id' => 999, // Un ID que no existe
        ];

        $response = $this->postJson('/api/videos', $data);

        $response->assertStatus(422)->assertJsonStructure(['error' => ['user_id']]);
    }

    //show videos
    public function returns_a_video_successfully(){
        // Crea un video en la base de datos
        $video = Video::factory()->create();

        $response = $this->getJson("/api/videos/{$video->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'showed' => true,
                    'data' => [
                        'id' => $video->id,
                        'title' => $video->title,
                        'description' => $video->description,
                        'youtube_url' => $video->youtube_url,
                        'user_id' => $video->user_id,
                    ],
                    'message' => 'Video obtenido exitosamente',
                ]);
    }
    //video no existe 
    public function returns_not_found_when_video_does_not_exist(){
        $nonExistentId = 999; // Suponiendo que no hay un video con este ID

        $response = $this->getJson("/api/videos/{$nonExistentId}");

        $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Video not found',
                ]);
    }
    //update video
    public function it_updates_a_video_successfully(){
        // Crea un video en la base de datos
        $video = Video::factory()->create();

        // Datos para actualizar el video
        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated description.',
            'youtube_url' => 'https://www.youtube.com/watch?v=updated',
            'user_id' => $video->user_id, // Asegúrate de usar un user_id válido
        ];

        // Realiza la petición PUT a la ruta correspondiente
        $response = $this->putJson("/api/videos/{$video->id}", $data);

        $response->assertStatus(200)
                ->assertJson([
                    'updated' => true,
                    'data' => [
                        'id' => $video->id,
                        'title' => 'Updated Title',
                        'description' => 'Updated description.',
                        'youtube_url' => 'https://www.youtube.com/watch?v=updated',
                        'user_id' => $video->user_id,
                    ],
                    'message' => 'Video actualizado exitosamente',
                ]);

        // Verificar que los cambios se reflejan en la base de datos
        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => 'Updated Title',
        ]);
    }


    public function returns_not_found_when_upd_video_does_not_exist(){
        // ID de un video que no existe
        $nonExistentId = 999; // Suponiendo que no hay un video con este ID

        // Datos válidos para actualizar
        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated description.',
            'youtube_url' => 'https://www.youtube.com/watch?v=updated',
            'user_id' => 1, // Suponiendo que existe un usuario con este ID
        ];

        // Realiza la petición PUT
        $response = $this->putJson("/api/videos/{$nonExistentId}", $data);

        // Afirmaciones
        $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Video not found',
                ]);
    }


    //delete video
    public function it_deletes_a_video_successfully(){
        // Crea un video en la base de datos
        $video = Video::factory()->create();

        // Realiza la petición DELETE
        $response = $this->deleteJson("/api/videos/{$video->id}");

        // Afirmaciones
        $response->assertStatus(200)
                ->assertJson([
                    'deleted' => true,
                    'message' => 'Video eliminado exitosamente'
                ]);

        // Verificar que el video ya no existe en la base de datos
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }

    public function returns_not_found_when_video_does_notexist(){
        // ID de un video que no existe
        $nonExistentId = 999; // Asegúrate de que este ID no exista

        // Realiza la petición DELETE
        $response = $this->deleteJson("/api/videos/{$nonExistentId}");

        $response->assertStatus(404)
                ->assertJson([
                    'message' => 'No query results for model [App\\Models\\Video] 999',
                ]);
    }

    //buscar
    public function searches_for_videos_successfully(){
        // Crea algunos videos en la base de datos
        $video1 = Video::factory()->create(['title' => 'Test Video One']);
        $video2 = Video::factory()->create(['title' => 'Another Test Video']);
        Video::factory()->create(['title' => 'Unrelated Video']);

        $response = $this->getJson('/api/videos/search?search=Test');

        $response->assertStatus(200)
                ->assertJson([
                    'listed' => true,
                    'message' => 'Resultados obtenidos exitosamente',
                ]);

        // Verificar que los videos relevantes estén en la respuesta
        $this->assertCount(2, $response->json('local_videos'));
        $this->assertEquals('Test Video One', $response->json('local_videos.0.title'));
        $this->assertEquals('Another Test Video', $response->json('local_videos.1.title'));
    }

    public function returns_empty_results_when_no_videos_found(){
        $response = $this->getJson('/api/videos/search?search=Nonexistent');

        $response->assertStatus(200)
                ->assertJson([
                    'listed' => true,
                    'message' => 'Resultados obtenidos exitosamente',
                ]);

        // Verificar que no haya videos en la respuesta
        $this->assertCount(0, $response->json('local_videos'));
    }

    public function it_increments_search_count_in_analytics(){
        // Crea un video con analíticas iniciales
        $video = Video::factory()->create(['title' => 'Test Video']);
        $analytics = new Analytics(['video_id' => $video->id, 'searches' => 5]);
        $video->analytics()->save($analytics);

        $response = $this->getJson('/api/videos/search?search=Test');

        // Verificar que se incrementó el contador de búsquedas
        $this->assertDatabaseHas('analytics', [
            'video_id' => $video->id,
            'searches' => 6, // Debería ser 5 + 1
        ]);
    }
}
