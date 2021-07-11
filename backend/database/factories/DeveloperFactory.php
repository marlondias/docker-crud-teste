<?php

namespace Database\Factories;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    public function definition(): array
    {
        $isMale = $this->faker->boolean;
        $hobbyActivity = $this->faker->randomElement(['Ciclismo', 'Caminhada', 'Artesanato', 'Fotografia']);
        $hobbyStyle = $this->faker->randomElement(['radical', 'experimental', 'relaxante']);
        $hobbyPlace = $this->faker->randomElement(['montanhas', 'desertos', 'florestas']);
    	return [
            'nome' => $this->faker->name($isMale ? 'male' : 'female'),
            'sexo' => $isMale ? 'M' : 'F',
            'idade' => $this->faker->numberBetween(0, 99),
            'hobby' => "{$hobbyActivity} {$hobbyStyle} em {$hobbyPlace}",
            'data_nascimento' => $this->faker->date()
    	];
    }
}
