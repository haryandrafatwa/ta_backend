<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class mahasiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = mahasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
			'mhs_nama' => $this->faker->unique()->name,
            'mhs_foto' => 'default-mahasiswa.jpg',
        ];
    }
}
