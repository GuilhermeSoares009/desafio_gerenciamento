<?php

use App\Models\User;
use App\Models\Pessoa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('pode listar todas as pessoas', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    Pessoa::factory()->count(3)->create();

    $response = $this->getJson('/api/pessoas');

    $response->assertStatus(200)
             ->assertJsonCount(3);
});

test('pode criar uma pessoa', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $data = [
        'nome' => 'João Silva',
        'cpf' => '123.456.789-00',
        'tipo' => 'fisica',
        'telefone' => '(11) 98765-4321',
        'email' => 'joao@email.com',
    ];

    $response = $this->postJson('/api/pessoas', $data);

    $response->assertStatus(201)
             ->assertJson($data);
});

test('validação falha ao criar pessoa sem campos obrigatórios', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/pessoas', []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['nome', 'cpf', 'tipo', 'telefone', 'email']);
});

test('pode visualizar uma pessoa específica', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $pessoa = Pessoa::factory()->create();

    $response = $this->getJson("/api/pessoas/{$pessoa->id}");

    $response->assertStatus(200)
             ->assertJson([
                 'id' => $pessoa->id,
                 'nome' => $pessoa->nome,
             ]);
});

test('pode atualizar uma pessoa', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $pessoa = Pessoa::factory()->create();

    $data = [
        'nome' => 'Nome Atualizado',
        'email' => 'novoemail@teste.com',
    ];

    $response = $this->putJson("/api/pessoas/{$pessoa->id}", $data);

    $response->assertStatus(200)
             ->assertJson($data);
});

test('pode deletar uma pessoa', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $pessoa = Pessoa::factory()->create();

    $response = $this->deleteJson("/api/pessoas/{$pessoa->id}");

    $response->assertStatus(204);
});

test('não pode acessar sem autenticação', function () {
    $response = $this->getJson('/api/pessoas');

    $response->assertStatus(401);
});
