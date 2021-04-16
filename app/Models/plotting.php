<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\dosen;

class plotting extends Model
{
    use HasFactory;
	
	protected $table = "plotting";
    public $timestamps = false;
    protected $primaryKey = "id";
    protected $keyType = 'int';
	use HasFactory;

    protected $fillable = [
        'id', 
        'nip_pembimbing_1',
        'nip_pembimbing_2',
        ];

    public function tbl_dosen()
    {
        return $this->belongsTo('App\Models\dosen', 'username');
    }
}
