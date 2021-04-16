<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class informasi extends Model
{
    use HasFactory;
    protected $table = "informasi";


    protected $fillable = [
        'informasi_id', 
        'informasi_judul',
        'informasi_isi', 
        'penerbit',      
        ];    
}
