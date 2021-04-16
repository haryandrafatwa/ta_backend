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
		$arr = array([1,null],[2,Carbon::now()->modify('+6 month')]);
		$select = $arr[array_rand($arr)];
        return [
            //
			'mhs_nama' => $this->faker->unique()->name,
            'plot_id' => \App\Models\plotting::all()->unique()->random()->id,
            'sk_expired' => $select[1],
			'sk_status' => $select[0],
            'mhs_foto' => 'default-mahasiswa.jpg',
        ];
    }
}
