<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlottingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plotting', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id();
            $table->string("nip_pembimbing_1");
            $table->string("nip_pembimbing_2");
            $table->foreign('nip_pembimbing_1')->references('dsn_nip')->on('dosen')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nip_pembimbing_2')->references('dsn_nip')->on('dosen')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plotting');
    }
}
