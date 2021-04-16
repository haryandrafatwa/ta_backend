<?php

namespace App\Models;
use App\Models\Judul;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\plotting;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class mahasiswa extends Model
{
    protected $table = "mahasiswa";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = "mhs_nim";
	use HasFactory;

    protected $fillable = [
        'mhs_nim',
        'mhs_nama',
        'angkatan',
        'mhs_kontak',
        'mhs_foto',
        'mhs_email',
        'plot_id',
        'sk_id',
        'username',
        ];


    public function tbl_plotting()
    {
        return $this->belongsTo('App\Models\plotting', 'id');
    }
	
	public function tbl_sk()
    {
        return $this->belongsTo('App\Models\sk_ta', 'id');
    }

    public function tbl_user()
    {
        return $this->hasOne('App\Models\user', 'username');
    }
}
