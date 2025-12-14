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
            'documento' => '12345678900',
            'tipo' => 'fisica',
            'telefone' => '11987654321',  
            'email' => 'joao.silva@email.com',
        ]);

        Pessoa::create([
            'nome' => 'Maria Santos',
            'documento' => '98765432100',  
            'tipo' => 'fisica',
            'telefone' => '11912345678',  
            'email' => 'maria.santos@email.com',
        ]);

        Pessoa::create([
            'nome' => 'Tech Solutions LTDA',
            'documento' => '12345678000190',  
            'tipo' => 'juridica',
            'telefone' => '1133334444',  
            'email' => 'contato@techsolutions.com.br',
        ]);
    }
}