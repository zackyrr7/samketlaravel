<?php

namespace App\Http\Controllers;
use App\Models\JenisTransaksi;
use Illuminate\Http\Request;

class JenisTransaksiController extends Controller
{
    public function index()
    {
        return JenisTransaksi::all();
    }

    public function store(Request $request)
    {
        try {
            JenisTransaksi::create([
                'nama'=>$request->nama,
            ]);
            return response()->json([
                'status'=> '200',
                'message' => "Jenis Transaksi Berhasil dibuat"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '500',
                'message' =>'Something went really wrong'
            ]);
        }
    }

    public function show($id)
    {
        $jenis = JenisTransaksi::find($id);
        if(!$jenis){
            return response()->json([
                'status' => '404',
                'message' => 'Jenis Transaksi tidak ditemukan'
            ]);
        } return response()-> json([
            'status' => '200',
            'message' => $jenis
        ]);
    }

    public function update(Request $request, $id){
        try{
            //menemukan jenis
            $jenis = JenisTransaksi::find($id);
            if(!$jenis){
                return response()->json([
                    'status' => '404',
                    'message' => 'jenis tidak ditemukan'
                ]);
            }

            // $jenis = jenis::find($id);
            $jenis->judul = $request->judul;
            $jenis->jawaban= $request->jawaban;
            $jenis->save();

            
            //update jenis
            // $jenis->save();

            //respon json
            return response()->json([
                'status' => '200',
                'message' => 'jenis Transaksi berhasil diupdate'
            ]);

        }catch(\Exception $e){
            return response()->json([
                'message' => "Something went really wrong"
            ]);
        }
    }
    public function destroy($id)
    {
        $jenis = JenisTransaksi::find($id);
        if(!$jenis) {
            return response()->json([
                'message' => "jenis tidak Ditemukan"
            ],404);
        }

        

        //delete barang
        $jenis->delete();

        return response()->json([
            'message' => "jenis berhasil di hapus"
        ]);
    }




    
}
