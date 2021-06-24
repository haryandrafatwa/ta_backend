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
            $table->string("judul")->nullable();
            $table->string("judul_inggris")->nullable();
            $table->unsignedBigInteger("plot_pembimbing")->nullable();
            $table->unsignedBigInteger("plot_penguji")->nullable();
            $table->string("username");
            $table->foreign('username')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('plot_pembimbing')->references('id')->on('plotting')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('plot_penguji')->references('id')->on('plotting')->onDelete('cascade')->onUpdate('cascade');
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
