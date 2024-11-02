<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

  
    public function registers_a_user(){
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => ['id', 'name', 'email', 'role', 'created_at', 'updated_at'],
                         'token'
                     ],
                 ]);

        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }

    public function logs_in_a_user(){
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => ['id', 'name', 'email', 'role', 'created_at', 'updated_at'],
                        //  'token'
                     ],
                 ]);
    }


    public function fails_to_register_with_invalid_data(){
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(400)->assertJsonStructure(['name', 'email', 'password', 'role']);
    }

    public function fails_to_login_with_invalid_credentials(){
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)->assertJson(['success' => false, 'message' => 'Credenciales incorrectas']);
    }
}
