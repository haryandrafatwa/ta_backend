<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class skta extends Model
{
    use HasFactory;
	protected $table = "skta";
    public $timestamps = false;
    protected $keyType = 'int';
    protected $primaryKey = "skta_id";
	
	protected $fillable = [
        'mhs_nim',
        'sk_status',
        'sk_terbit',
        'sk_expired',
        ];
		
}
