<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidang', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id('sidang_id');
			$table->string('sidang_review')->nullable();
            $table->timestamp('sidang_tanggal')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_berakhir')->nullable();
            $table->unsignedBigInteger('nilai_penguji_1')->nullable();
            $table->unsignedBigInteger('nilai_penguji_2')->nullable();
            $table->unsignedBigInteger('nilai_pembimbing_1')->nullable();
            $table->unsignedBigInteger('nilai_pembimbing_2')->nullable();
            $table->string('nilai_total')->nullable();
            $table->string('sidang_status');
            $table->string('mhs_nim');
            $table->foreign('nilai_penguji_1')->references('nilai_id')->on('nilai')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nilai_penguji_2')->references('nilai_id')->on('nilai')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nilai_pembimbing_1')->references('nilai_id')->on('nilai')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nilai_pembimbing_2')->references('nilai_id')->on('nilai')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mhs_nim')->references('username')->on('mahasiswa')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sidang');
    }
}
