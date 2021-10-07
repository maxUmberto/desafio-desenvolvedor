<?php

namespace Tests\Feature\Login;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// models
use App\Models\User;

class UserSignUpTest extends TestCase {
    
    use DatabaseMigrations;

    const SIGN_UP_DATA = [
        "first_name" => "Max",
        "last_name"  => "Santos",
        "email"      => "teste@gmail.com",
        "password"   => 123456
    ];

    public function testCantCreateUserWithoutFirstName(){
        $data = self::SIGN_UP_DATA;
        unset($data['first_name']);
    
        $response = $this->postJson('/api/sign-up', $data);
    
        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'first_name'
                    ]
                ]);
    }

    public function testCantCreateUserWithoutLastName() {
        $data = self::SIGN_UP_DATA;
        unset($data['last_name']);
    
        $response = $this->postJson('/api/sign-up', $data);
    
        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'last_name'
                    ]
                ]);
    }

    public function testCantCreateUserWithoutEmail() {
        $data = self::SIGN_UP_DATA;
        unset($data['email']);
    
        $response = $this->postJson('/api/sign-up', $data);
    
        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'email'
                    ]
                ]);
    }

    public function testCantCreateUserWithoutPassword() {
        $data = self::SIGN_UP_DATA;
        unset($data['password']);
    
        $response = $this->postJson('/api/sign-up', $data);
    
        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'password'
                    ]
                ]);
    }

    public function testCantCreateUserWithAlreadyRegisteredMail() {
        User::factory()->create([
          'email' => self::SIGN_UP_DATA['email']
        ]);
    
        $data = self::SIGN_UP_DATA;
    
        $response = $this->postJson('/api/sign-up', $data);
    
        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'email'
                    ]
                ])
                ->assertJsonFragment([
                    'email' => [
                        'Esse email já está cadastrado'
                    ]
                ]);
    }

    public function testCanCreateUser() {

        $data = self::SIGN_UP_DATA;
    
        $response = $this->postJson('/api/sign-up', $data);
    
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'token_type',
                    'token',
                    'message',
                    'success'
                ])
                ->assertJsonFragment(['token_type' => 'bearer'])
                ->assertJsonFragment(['message' => 'Usuário cadastrado com sucesso'])
                ->assertJsonFragment(['success' => true]);
    
        $this->assertDatabaseHas('users', [
            'first_name' => self::SIGN_UP_DATA['first_name'],
            'last_name'  => self::SIGN_UP_DATA['last_name'],
            'email'      => self::SIGN_UP_DATA['email'],
        ]);
    }

}
