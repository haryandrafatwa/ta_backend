<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sidang extends Model
{
    protected $table = "sidang";
    public $timestamps = false;
    protected $primaryKey = "sidang_id";

    protected $fillable = [
        'sidang_id', 
        'sidang_review',
        'sidang_tanggal', 
        'nilai_penguji_1', 
        'nilai_penguji_2', 
        'nilai_pembimbing_1', 
        'nilai_pembimbing_2', 
        'nilai_total', 
        'sidang_status',
        'mhs_nim',
        ];

    public function tbl_mhs(){
        return $this->belongsTo('App\Models\mahasiswa', 'username');
    }
}
