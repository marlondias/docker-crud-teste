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
    	return [
            'nome' => $this->faker->name($isMale ? 'male' : 'female'),
            'sexo' => $isMale ? 'M' : 'F',
            'idade' => $this->faker->numberBetween(0, 99),
            'hobby' => $this->faker->words(),
            'data_nascimento' => $this->faker->date()
    	];
    }
}
