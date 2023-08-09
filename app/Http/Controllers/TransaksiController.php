<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Tabungan;

class TransaksiController extends Controller
{
    public function index()
    {
        return Transaksi::all();
    }


    public function store(Request $request)
    {
        try {
            $transaksi = new Transaksi();
            $transaksi->jenis_transaksis_id = $request->jenis_transaksis_id;
            $transaksi->total = $request->total;
            $transaksi->tanggal = Carbon::now();
            $transaksi->nomor = $request->nomor;
            $transaksi->jenis = $request->jenis;
            $transaksi->status = 'sedang diverifikasi';
            $transaksi->users_id = $request->users_id;
            $transaksi->save();

            //
            $tabungan = Tabungan::where('users_id', $request->users_id)->get();
            $transaksi2 = Transaksi::where('users_id', $request->users_id)->get();
            $totaltransaksi = 0;
            foreach ($transaksi2 as $trs) {
                $totaltransaksi += $trs->total;
            }
            $totaltabungan = 0;
            foreach ($tabungan as $tbg) {
                $totaltabungan += $tbg->saldo;
            }
            if ($totaltabungan   <  $totaltransaksi) {
                $transaksi = Transaksi::find($transaksi->id);
                $transaksi->delete();
                return response()->json([
                    'total_transaksi' => $totaltransaksi,
                    'total_tabungan' =>$totaltabungan,
                    'status' => "201",
                    'message' => "Transaksi gagal",
                    'message2' => "Saldo tidak cukup"
                ]);
            }



            return response()->json([
                'status' => "200",
                'message' => "Transaksi berhasil ditambahkan",
                'message2' => "Transaksimu akan di verifikasi"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => "500",
                'message' => "something went really wrong"
            ]);
        }
    }

    public function selesai($id)
    {
        try {
            $transaksi = Transaksi::find($id);
            if (!$transaksi) {
                return response()->json([
                    'status' => '404',
                    'message' => 'transaksi tidak ditemukan'
                ]);
            }

            $transaksi->status = 'Selesai';
            $transaksi->save();

            return response()->json([
                'status' => '200',
                'message' => 'Transaksi telah selesai'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong"
            ]);
        }
    }

    public function indexuser($id)
    {
        try {
            $user = User::find($id);
              
            //     $transaksi = Transaksi::where('users_id', $id)->get();
            //    $transaksi = Transaksi::with('jenistransaksi')->get()->jenistransaksi->nama;
            $transaksi = Transaksi::with('jenistransaksi')->where('users_id', $id)->get();
              
            //    $transaksi = Transaksi::with('jenis_transaksis')->whereRelation('jenis_transaksis', $id)->get();


             return $transaksi;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 201,
                'message' => 'Server Error'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::find($id);
            if (!$transaksi) {
                return response()->json([
                    'status' => '404',
                    'message' => 'transaksi tidak ditemukan'
                ]);
            }
            $transaksi->jenis_transaksis_id = $request->jenis_transaksis_id;
            $transaksi->total = $request->total;
            $transaksi->tanggal = Carbon::now();
            $transaksi->nomor = $request->nomor;
            $transaksi->jenis = $request->jenis;
            $transaksi->status = 'sedang diverifikasi';
            $transaksi->users_id = $request->users_id;
            $transaksi->save();

            return response()->json([
                'status' => '200',
                'message' => 'Transaksi berhasil di Update'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong"
            ]);
        }
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json([
                'message' => "Transaksi tidak Ditemukan"
            ], 404);
        }



        //delete barang
        $transaksi->delete();

        return response()->json([
            'message' => "transaksi berhasil di hapus"
        ]);
    }
}
