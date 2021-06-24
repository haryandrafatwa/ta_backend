<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nilai extends Model
{
    use HasFactory;
    protected $table = "nilai";
    public $timestamps = false;
    protected $primaryKey = "nilai_id";
    protected $keyType = 'int';
	protected $hidden = ['created_at','updated_at'];
	
	protected $fillable = [
        'nilai_id', 
        'clo_1',
        'clo_2',
        'clo_3', 
        ];
}
