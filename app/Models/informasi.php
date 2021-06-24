<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class informasi extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = "informasi";
    protected $primaryKey = "informasi_id";
    protected $keyType = 'integer';


    protected $fillable = [
        'informasi_id', 
        'informasi_judul',
        'informasi_isi', 
        'penerbit',      
        ];    
}
