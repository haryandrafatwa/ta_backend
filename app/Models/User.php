<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class user extends Authenticatable
{
    protected $table = "users";
    public $timestamps = true;
    protected $primaryKey = "username";
    protected $keyType = 'string';
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'username', 
        'password',
        'pengguna', 
        ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
	
	protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tbl_koordinator()
    {
        return $this->hasOne('App\Models\koordinator', 'username');
    }

    public function tbl_dosen()
    {
        return $this->hasOne('App\Models\dosen', 'username');
    }

    public function tbl_mahasiswa()
    {
        return $this->hasOne('App\Models\mahasiswa', 'username');
    }

}
