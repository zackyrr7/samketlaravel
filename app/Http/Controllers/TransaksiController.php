<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;


class TransaksiController extends Controller
{
    public function index()
    {
        return Transaksi::all();
    }

    public function store(Request $request)
    {
       try {
        Transaksi::create([
            'tanggal'=> $request->tanggal,
            'total' => $request->total,
            'nomor' => $request->nomor,
            'jenis' => $request->jenis,
            'jenistransaksis_id' => $request->jenistransaksis_id,
        ]);
         return response()->json([
            'status' => "200",
            'message' => "Transaksi Dibuat"
         ]);
       } catch (\Exception $e) {
        return response()->json([
            'status' => "500",
            'message'=> "something went really wrong"
        ]);
       }
    }

    public function storeSedekah(Request $request)
    {
       try {
        Transaksi::create([
            'total' => $request->total,
            'jenistransaksis_id' => $request->jenistransaksis_id,
        ]);
         return response()->json([
            'status' => "200",
            'message' => "Transaksi Dibuat"
         ]);
       } catch (\Exception $e) {
        return response()->json([
            'status' => "500",
            'message'=> "something went really wrong"
        ]);
       }
    }

}
