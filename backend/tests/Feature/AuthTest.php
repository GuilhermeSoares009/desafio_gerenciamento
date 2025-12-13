<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);
});

test('usuário pode fazer login com credenciais válidas', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'access_token',
                 'token_type',
                 'user' => ['id', 'name', 'email']
             ]);
});

test('login falha com credenciais inválidas', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'senhaerrada',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

test('usuário pode fazer logout', function () {
    $token = $this->user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                     ->postJson('/api/logout');

    $response->assertStatus(200);
});