<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dosen', function (Blueprint $table) {
			      $table->engine = 'InnoDB';
            $table->string('dsn_nip')->primary();
            $table->string('dsn_nama');
            $table->string('dsn_kode')->unique();
            $table->string('dsn_kontak')->unique()->nullable();
            $table->string('dsn_email')->unique()->nullable();
            $table->text('dsn_foto');
            $table->string('username');
            $table->timestamps();
			      $table->foreign('username')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dosen');
    }
}
