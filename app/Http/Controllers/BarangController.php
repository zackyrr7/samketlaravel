<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
       return Barang::all();
    }

    public function store(Request $request)
    {
        try {
            $imageName = Str::random(32) . "." . $request->foto->getClientOriginalExtension();
            Barang::create([
                'nama' => $request->nama,
                'harga' => $request->harga,
                'foto' => $imageName
            ]);
            Storage::disk('public')->put($imageName, file_get_contents($request->foto));
            $url = Storage::url("/storage/app/{$imageName}");
            $path = public_path($url);

            //Json Response
            return response()->json([
                'status' => "200",
                'message' => "Barang berhasil ditambahkan",


            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "something went really wrong"
            ], 500);
        }
    }

    public function show($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json([
                'message' => 'Barang Tidak ditemukan'
            ],);
        }
        return response()->json([

            'barang' => $barang
        ]);
    }

    public function update(Request $request, $id)
    {
        //barang detail
        try{
            //menemukan barang
            $barang = Barang::find($id);
            if(!$barang){
                return response()->json([
                    'message' => 'Barang tidak ditemukan'
                ],404);
            }

            $barang->nama = $request->nama;
            $barang->harga= $request->harga;

            if ($request->foto){
                $storage = Storage::disk('public');

                //hapus foto lama
                if ($storage->exists($barang->foto))
                $storage->delete($barang->foto);

                //nama foto
                $imageName = Str::random(32).".".$request->foto->getClientOriginalExtension();
                $barang->foto = $imageName;

                //save foto
                $storage->put($imageName, file_get_contents($request->foto));
            }
            //update barang
            $barang->save();

            //respon json
            return response()->json([
                'message' => 'Barang berhasil diupdate'
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => "Something went really wrong"
            ]);
        }
      
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        if(!$barang) {
            return response()->json([
                'message' => "Barang tidak Ditemukan"
            ],404);
        }

        //hapus storage
        $storage = Storage::disk('public');

        //hapus gambar
        if($storage->exists($barang->foto))
        $storage->delete($barang->foto);

        //delete barang
        $barang->delete();

        return response()->json([
            'message' => "Barang berhasil di hapus"
        ]);
    }
}
