<?php

use App\Models\User;
use App\Models\Pessoa;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'sanctum');
});

test('pode listar todas as pessoas', function () {
    Pessoa::factory()->count(3)->create();

    $response = $this->getJson('/api/pessoas');

    $response->assertStatus(200)
             ->assertJsonCount(3);
});

test('pode criar uma pessoa', function () {
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

    $this->assertDatabaseHas('pessoas', ['email' => 'joao@email.com']);
});

test('validação falha ao criar pessoa sem campos obrigatórios', function () {
    $response = $this->postJson('/api/pessoas', []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['nome', 'cpf', 'tipo', 'telefone', 'email']);
});

test('pode visualizar uma pessoa específica', function () {
    $pessoa = Pessoa::factory()->create();

    $response = $this->getJson("/api/pessoas/{$pessoa->id}");

    $response->assertStatus(200)
             ->assertJson([
                 'id' => $pessoa->id,
                 'nome' => $pessoa->nome,
             ]);
});

test('pode atualizar uma pessoa', function () {
    $pessoa = Pessoa::factory()->create();

    $data = [
        'nome' => 'Nome Atualizado',
        'email' => 'novoemail@teste.com',
    ];

    $response = $this->putJson("/api/pessoas/{$pessoa->id}", $data);

    $response->assertStatus(200)
             ->assertJson($data);

    $this->assertDatabaseHas('pessoas', ['email' => 'novoemail@teste.com']);
});

test('pode deletar uma pessoa', function () {
    $pessoa = Pessoa::factory()->create();

    $response = $this->deleteJson("/api/pessoas/{$pessoa->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('pessoas', ['id' => $pessoa->id]);
});

test('não pode acessar sem autenticação', function () {
    $this->withoutMiddleware(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
    
    $response = $this->getJson('/api/pessoas');

    $response->assertStatus(401);
});