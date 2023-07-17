<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tabungan;
use App\Models\Transaksi;
use Carbon\Carbon;
use App\Models\User;

class TabunganController extends Controller
{


    public function index()
    {
        return Tabungan::all();
    }




    public function store(Request $request)
    {
        try {
            $tabungan = new Tabungan();
            $tabungan->users_id = $request->users_id;
            $tabungan->saldo = $request->saldo;
            $tabungan->status = 'Penambahan saldo tabungan';
            $tabungan->tanggal = Carbon::now();
            $tabungan->save();
            return response()->json([
                'status' => "200",
                'message' => "Tabungan berhasil ditambahkan"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => "500",
                'message' => "something went really wrong"
            ]);
        }
    }

    public function indexuser($id)
    {
        try {
            $user = User::find($id);
            
            $tabungan = Tabungan::where('users_id', $id)->get();
            $totaltabungan = 0;
            foreach ($tabungan as $tbg){
                $totaltabungan += $tbg->saldo;
            }
            return response()->json([
                'status' =>'200',
                'total' => $totaltabungan,
                'data' => $tabungan
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 201,
                'message' => 'Server Error'
            ]);
        }
    }

    public function total($id)
    {
        try {
            $user = User::find($id);
            
            $tabungan = Tabungan::where('users_id', $id)->get();
            $transaksi = Transaksi::where('users_id', $id)->get();
            $totaltransaksi = 0;
            foreach ($transaksi as $trs){
                $totaltransaksi += $trs->total;
            }
            $totaltabungan = 0;
            foreach ($tabungan as $tbg){
                $totaltabungan += $tbg->saldo;
            }
            return response()->json([
                'status' =>'200',
                'total' => $totaltabungan - $totaltransaksi,
                'tabungan' => $tabungan,
                'transaksi' =>$transaksi

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 201,
                'message' => 'Server Error'
            ]);
        }
    }

    public function destroy($id)
    {
        $tabungan = Tabungan::find($id);
        if(!$tabungan) {
            return response()->json([
                'message' => "Tabungan tidak Ditemukan"
            ],404);
        }

        //delete barang
        $tabungan->delete();

        return response()->json([
            'message' => "tabungan berhasil di hapus"
        ]);
    }

}
