<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pessoa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('123456'),
        ]);

        Pessoa::create([
            'nome' => 'João Silva',
            'cpf' => '123.456.789-00',
            'tipo' => 'fisica',
            'telefone' => '(11) 98765-4321',
            'email' => 'joao.silva@email.com',
        ]);

        Pessoa::create([
            'nome' => 'Maria Santos',
            'cpf' => '987.654.321-00',
            'tipo' => 'fisica',
            'telefone' => '(11) 91234-5678',
            'email' => 'maria.santos@email.com',
        ]);

        Pessoa::create([
            'nome' => 'Tech Solutions LTDA',
            'cpf' => '12.345.678/0001-90',
            'tipo' => 'juridica',
            'telefone' => '(11) 3333-4444',
            'email' => 'contato@techsolutions.com.br',
        ]);
    }
}