<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class dosen extends Model
{
    protected $table = "dosen";
    public $timestamps = true;
    protected $primaryKey = "dsn_nip";
    protected $keyType = 'string';
	use HasFactory;

    protected $fillable = [
        'dsn_nip', 
        'dsn_nama',
        'dsn_kode',
        'dsn_kontak', 
        'dsn_foto', 
        'dsn_email',
        'username',
        ];

    public function tbl_user()
    {
        return $this->belongsTo('App\Models\user', 'username');
    }
    
}

