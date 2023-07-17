<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'jenis_transaksis_id',
        'total',
        'tanggal',
        'nomor',
        'jenis',
        'status',
        'users_id'
        

    ];

    public function jenistransaksi()
    {
        return $this->belongsTo(JenisTransaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
