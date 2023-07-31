<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pesan extends Model
{
    use HasFactory;
    protected $fillable =[
        
        'users_id',
        'tanggal',
        'foto',
        'foto2',
        'foto3',
        'jenis',
        'alamat'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
}
