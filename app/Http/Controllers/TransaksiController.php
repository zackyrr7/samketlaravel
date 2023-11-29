<?php

namespace App\Http\Controllers;

use App\Models\JenisTransaksi;
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
    public function indexVerif()
    {
        $transaksi = Transaksi::where('status', 'sedang diverifikasi')->get();
        return $transaksi;
    }
    public function indexSelesai()
    {
        $transaksi = Transaksi::where('status', 'Selesai')->get();
        return $transaksi;
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
                    'total_tabungan' => $totaltabungan,
                    'status' => "201",
                    'message' => "Transaksi gagal",
                    'message2' => "Saldo tidak cukup"
                ]);
            }

            $user = User::find($transaksi->users_id)->first();
            $nama = $user->name;
            $nomor = $user->no_hp;

            $jenis = JenisTransaksi::find($transaksi->jenis_transaksis_id)->first();
            $namaJenis = $jenis->nama;

            $token  = 'Yv!9GI2jH2y4f-r!r_Ha';
            $target = '6281347771171';
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $target,
                    "message" => "Transaksi \nJenis: $namaJenis \ntanggal: $transaksi->tanggal \ntotal: $transaksi->total \nnomor: $transaksi->nomor \njenis: $transaksi->jenis \nPemesan Nama: $nama, \nNomor Hp: $nomor",
                    'delay' => '2-5',
                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $token" //change TOKEN to your actual token
                ),
            ));

            $responses = curl_exec($curl);

            curl_close($curl);



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


            $transaksi = Transaksi::with('jenistransaksi')->where('users_id', $id)->get();


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

    public function emas(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::find($id);
            if (!$transaksi) {
                return response()->json([
                    'status' => '404',
                    'message' => 'transaksi tidak ditemukan'
                ]);
            }

            $transaksi->total = $request->total;


            $transaksi->status = 'Selesai';

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
