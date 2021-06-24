<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class koordinator extends Model
{
    protected $table = "koordinator";
    public $timestamps = true;
    protected $primaryKey = "koor_nip";
    protected $keyType = 'string';
	use HasFactory;
	protected $hidden = ['created_at','updated_at'];

    protected $fillable = [
        'koor_nip', 
        'koor_nama',
        'koor_kode',
        'koor_kontak', 
        'koor_foto', 
        'koor_email',
        'username',
        ];

    public function tbl_user()
    {
        return $this->belongsTo('App\Models\user', 'username');
    }
    
}

