<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifikasi', function (Blueprint $table) {
			      $table->engine = 'InnoDB';
            $table->id("notifikasi_id");
            $table->string("notifikasi_dari");
            $table->string("notifikasi_untuk");
            $table->string("notifikasi_kategori");
            $table->text("notifikasi_deskripsi");
            $table->tinyInteger("notifikasi_baca");
            $table->timestamp("notifikasi_tanggal")->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifikasi');
    }
}
