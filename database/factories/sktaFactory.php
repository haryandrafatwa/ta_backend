<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\skta;
use Illuminate\Database\Eloquent\Factories\Factory;

class sktaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = skta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
			'sk_status' => 1,
        ];
    }
}
