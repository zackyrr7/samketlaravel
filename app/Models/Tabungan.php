<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'users_id',
    ];


    public function jenistransaksis()
    {
        return $this->hasMany(JenisTransaksi::class, 'tabungans_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
