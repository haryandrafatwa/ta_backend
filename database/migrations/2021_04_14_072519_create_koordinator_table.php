<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoordinatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koordinator', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->string('koor_nip')->primary();
            $table->string('koor_nama');
            $table->string('koor_kode')->unique();
            $table->string('koor_kontak')->unique()->nullable();
            $table->string('koor_email')->unique()->nullable();
            $table->text('koor_foto');
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
        Schema::dropIfExists('koordinator');
    }
}
