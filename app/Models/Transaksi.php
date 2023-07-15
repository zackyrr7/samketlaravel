<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'jenistransaksis_id',
        'total',
        'tanggal',
        'nomor',
        'jenis'

    ];

    public function jenistransaksi()
    {
        return $this->belongsTo(JenisTransaksi::class);
    }
}
