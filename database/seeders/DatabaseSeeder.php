<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create(['pengguna' => 'LAK','username' => 'admin_lak']);
		//---------------------------------------------------------------------------------------------
        \App\Models\User::factory(1)->create(['pengguna' => 'prodi','username' => 'admin_prodi']);
		//---------------------------------------------------------------------------------------------
		DB::table("sk_status")->insert(['status' => 'Belum terbit']);
		DB::table("sk_status")->insert(['status' => 'Sudah terbit']);
		DB::table("sk_status")->insert(['status' => 'Kadaluwarsa']);
		DB::table("sk_status")->insert(['status' => 'Perpanjang 1']);
		DB::table("sk_status")->insert(['status' => 'Perpanjang 2']);
		DB::table("sk_status")->insert(['status' => 'Perpanjang 3']);
		$faker = Faker::create('id_ID');
		$kodeList = ["DWM","SYP","YPR","GAW","SMD","SWD","RYJ","UIW","NKN","VRE"];
		for($i =0;$i < 10; $i++){
			$username = $faker->unique()->numberBetween(1000000000,9999999999);
			\App\Models\User::factory(1)->create(['pengguna' => 'dosen','username'=> $username]);
			\App\Models\dosen::factory(1)->create(['username' => $username,'dsn_nip' => $username, 'dsn_kode' => $kodeList[$i]]);
		}
		// \App\Models\plotting::factory(5)->create()->unique();
		$usernameList = ["1301174007","1301174065","1301174029","1301174223","1301198518"];
		for($i =0;$i < 5; $i++){
			$username = $usernameList[$i];
			\App\Models\User::factory(1)->create(['pengguna' => 'mahasiswa','username' => $username]);
			\App\Models\mahasiswa::factory(1)->create(['username' => $username,'mhs_nim' => $username,'angkatan' => '20'. substr($username, 4,2)]);
			\App\Models\skta::factory(1)->create(['mhs_nim' => $username]);
		}
        \App\Models\koordinator::factory(1)->create(['username' => 'admin_lak']);
        \App\Models\koordinator::factory(1)->create(['username' => 'admin_prodi']);
    }
}
