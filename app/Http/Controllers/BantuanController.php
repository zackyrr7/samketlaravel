<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pertanyaan;

class BantuanController extends Controller
{
    public function index()
    {
        return Pertanyaan::all();
    }


    public function store(Request $request){
        try{
           
            //create barang
            Pertanyaan::create([
                'judul' =>$request->judul,
                'jawaban'=> $request->jawaban,
                
            ]);
             //return Pertanyaan::create($request->all());
       
        
            //Json Response
            return response()->json([
                'status' => "200",
                'message' => "Pertanyaan berhasil ditambahkan"
            ]);
        }catch(\Exception $e) {
            return response()->json([
                'status' => "500",
                'message' => "something went really wrong"
            ]);
        }
    }

    public function show($id)
    {
        //return pertanyaan::find($id);
        //detail pertanyaan
        $pertanyaan = Pertanyaan::find($id);
        if (!$pertanyaan){
            return response()->json([
                'status' => '404',
                'message' => 'pertanyaan Tidak ditemukan'
            ],);
        }return response() ->json([
            'status' => '200',
            'pertanyaan' => $pertanyaan
        ]);
    }

    public function update(Request $request, $id){
        try{
            //menemukan pertanyaan
            $pertanyaan = Pertanyaan::find($id);
            if(!$pertanyaan){
                return response()->json([
                    'status' => '404',
                    'message' => 'pertanyaan tidak ditemukan'
                ]);
            }

            // $pertanyaan = Pertanyaan::find($id);
            $pertanyaan->judul = $request->judul;
            $pertanyaan->jawaban= $request->jawaban;
            $pertanyaan->save();

            
            //update pertanyaan
            // $pertanyaan->save();

            //respon json
            return response()->json([
                'status' => '200',
                'message' => 'Pertanyaan berhasil diupdate'
            ]);

        }catch(\Exception $e){
            return response()->json([
                'message' => "Something went really wrong"
            ]);
        }
    }
    public function destroy($id)
    {
        $pertanyaan = Pertanyaan::find($id);
        if(!$pertanyaan) {
            return response()->json([
                'message' => "Pertanyaan tidak Ditemukan"
            ],404);
        }

        

        //delete barang
        $pertanyaan->delete();

        return response()->json([
            'message' => "pertanyaan berhasil di hapus"
        ]);
    }
}
