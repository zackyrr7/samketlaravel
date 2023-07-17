<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;
    protected $fillable = [
        'users_id',
        'saldo',
        'status',
        'tanggal'
        
        
    ];


    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
