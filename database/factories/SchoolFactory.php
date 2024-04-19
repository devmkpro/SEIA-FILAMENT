<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word,
            'type' => 'Municipal',
            'category' => 'Creche',
            'name' => $this->faker->word,
            'email' => $this->faker->safeEmail,
            'address' => $this->faker->word,
            'zip_code' => $this->faker->numerify('#####-###'),
            'phone' => $this->faker->numerify('(##) ####-####'),
            'neighborhood' => $this->faker->word,
            'landline' => $this->faker->numerify('(##) ####-####'),
            'cnpj' => $this->faker->numerify('##.###.###/####-##'),
            'complement' => $this->faker->word,
            'acronym' => $this->faker->word,
            'city_id' => \App\Models\City::first()->id,
        ];
    }
}
