<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSktaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skta', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id('skta_id');
			$table->string('mhs_nim');
			$table->smallInteger('sk_status')->default(1);
            $table->timestamp('sk_terbit')->nullable();
            $table->timestamp('sk_expired')->nullable();
            $table->foreign('sk_status')->references('id')->on('sk_status')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('skta');
    }
}
