<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBimbinganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbingan', function (Blueprint $table) {
			      $table->engine = 'InnoDB';
            $table->id('bimbingan_id');
            $table->text('bimbingan_review')->nullable();
            $table->timestamp('bimbingan_tanggal');
            $table->string('bimbingan_status');
            $table->string('dsn_nip');
            $table->string('mhs_nim');
			      $table->foreign('dsn_nip')->references('dsn_nip')->on('dosen')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mhs_nim')->references('mhs_nim')->on('mahasiswa')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bimbingan');
    }
}
