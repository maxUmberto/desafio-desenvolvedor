<?php

namespace Tests\Feature\Login;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// models
use App\Models\User;

class UserLogoutTest extends TestCase {

    use DatabaseMigrations;

    public function testCantLogoutWithoutBearerToken() {

        $user = User::factory()->create();
    
        $response = $this->post('/api/logout');
    
        $response->assertStatus(412)
                ->assertJsonStructure([
                    'message',
                    'success'
                ])
                ->assertJsonFragment(['message' => 'É necessário informar um token'])
                ->assertJsonFragment(['success' => false]);
    }

    public function testCanLogout() {

        $user = User::factory()->create();
    
        $response = $this->actingAs($user)
                         ->post('/api/logout');
    
        $response->assertStatus(204);
    }
    
}
