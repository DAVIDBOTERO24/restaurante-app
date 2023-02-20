<?php

namespace Database\Factories;

use App\Models\Unidad;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnidadFactory extends Factory
{
    protected $model = Unidad::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'unidad' => $this->faker->unique()->firstname,
            'abreviacion' => Str::random(2)
        ];
    }
}
