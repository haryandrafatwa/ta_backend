<?php

namespace Database\Factories;

use App\Models\koordinator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class koordinatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = koordinator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'koor_nip' => $this->faker->unique()->numberBetween(1000000000,9999999999),
            'koor_nama' => $this->faker->unique()->name,
            'koor_kode' => $this->faker->regexify('[A-Z]{3}'),
            'koor_foto' => 'default-koor.jpg',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
