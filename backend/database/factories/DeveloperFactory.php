<?php

namespace Database\Factories;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    public function definition(): array
    {
        $isMale = $this->faker->boolean;
        $hobbyActivity = $this->faker->randomElement(['Ciclismo', 'Caminhada', 'Artesanato', 'Fotografia']);
        $hobbyStyle = $this->faker->randomElement(['radical', 'experimental', 'relaxante']);
        $hobbyPlace = $this->faker->randomElement(['montanhas', 'desertos', 'florestas']);
        $birthDate = $this->faker->date();
        $age = Date::createFromFormat('Y-m-d', $birthDate)->diffInYears(Date::now());
    	return [
            'nome' => $this->faker->name($isMale ? 'male' : 'female'),
            'sexo' => $isMale ? 'M' : 'F',
            'data_nascimento' => $birthDate,
            'idade' => $age,
            'hobby' => "{$hobbyActivity} {$hobbyStyle} em {$hobbyPlace}",
    	];
    }
}
