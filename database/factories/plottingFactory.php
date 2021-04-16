<?php

namespace Database\Factories;

use App\Models\plotting;
use Illuminate\Database\Eloquent\Factories\Factory;

class plottingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = plotting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
		$d1 = \App\Models\dosen::all()->unique()->random()->username;
		$d2 = \App\Models\dosen::all()->unique()->random()->username;
		while($d2 == $d1){
			$d2 = \App\Models\dosen::all()->random()->username;
		}
        return [
            //
			'nip_pembimbing_1' => $d1,
			'nip_pembimbing_2' => $d2
        ];
    }
}
