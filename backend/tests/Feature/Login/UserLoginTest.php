<?php

namespace Tests\Feature\Login;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// models
use App\Models\User;

class UserLoginTest extends TestCase {

    use DatabaseMigrations;

    public function testCantLoginWithoutSignUp() {

        $user = User::factory()->make();
    
        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => $user->password
        ]);
    
        $response->assertStatus(404);
    }

    public function testCantLoginWithWrongPassword() {

        $user = User::factory()->create();
    
        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'password2'
        ]);
    
        $response->assertStatus(401)
                ->assertJsonStructure([
                    'message',
                    'success'
                ])
                ->assertJsonFragment(['message' => 'Email ou senha incorretos'])
                ->assertJsonFragment(['success' => false]);
    }

    public function testCanLogin() {
        $user = User::factory()->create();
    
        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'success',
                    'token',
                    'token_type'
                ])
                ->assertJsonFragment(['success' => true]);
    }
    
}
