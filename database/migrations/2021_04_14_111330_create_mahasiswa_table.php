<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
			      $table->engine = 'InnoDB';
            $table->string("mhs_nim")->primary();
            $table->string("mhs_nama");
            $table->string("angkatan");
            $table->string("mhs_kontak")->unique()->nullable();
            $table->string("mhs_foto");
            $table->string("mhs_email")->unique()->nullable();
            $table->unsignedBigInteger("plot_id")->nullable();
            $table->timestamp("sk_expired")->nullable();
            $table->smallInteger("sk_status")->default(1);
            $table->string("username");
            $table->foreign('username')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('plot_id')->references('id')->on('plotting')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sk_status')->references('id')->on('sk_status')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswa');
    }
}
