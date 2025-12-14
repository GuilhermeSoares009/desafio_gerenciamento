<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pessoa>
 */
class PessoaFactory extends Factory
{
    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['fisica', 'juridica']);
        return [
            'nome' => $this->faker->name(),
            'documento' => $tipo === 'fisica' 
                ? $this->faker->numerify('###########') 
                : $this->faker->numerify('##############'),
            'tipo' => $tipo,
            'telefone' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}