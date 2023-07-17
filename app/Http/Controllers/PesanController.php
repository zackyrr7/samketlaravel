<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pesan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PesanController extends Controller
{
    public function index()
    {
        return Pesan::all();
    }

    public function store(Request $request)
    {
        try {
            $file = $request->file('foto');
            $filename = time() . '-' . $file->getClientOriginalName();
            $request->foto->move(public_path('storage'), $filename);
            Pesan::create([
                'users_id' => $request->users_id,
                'tanggal' => $request->tanggal,
                'alamat' => $request->alamat,
                'foto' => $filename
            ]);


            //Json Response
            return response()->json([
                'status' => "200",
                'message' => "Pesanan berhasil ditambahkan",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "something went really wrong"
            ], 500);
        }
    }

    public function show($id)
    {
        $pesan = Pesan::find($id);
        if (!$pesan) {
            return response()->json([
                'message' => 'Pesanan Tidak ditemukan'
            ],);
        }
        return response()->json([

            'pesan' => $pesan
        ]);
    }

    public function update(Request $request, $id)
    {
        //pesan detail
        try {
            //menemukan pesan
            $pesan = Pesan::find($id);
            if (!$pesan) {
                return response()->json([
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            $pesan->users_id = $request->users_id;
            $pesan->tanggal = $request->tanggal;
            $pesan->alamat = $request->alamat;


            if ($request->foto) {
                $storage = Storage::disk('public');

                //hapus foto lama
                if ($storage->exists($pesan->foto))
                    $storage->delete($pesan->foto);

                //nama foto
                $imageName = Str::random(32) . "." . $request->foto->getClientOriginalExtension();
                $pesan->foto = $imageName;

                //save foto
                $storage->put($imageName, file_get_contents($request->foto));
            }
            //update pesan
            $pesan->save();

            //respon json
            return response()->json([
                'message' => 'Pesan berhasil diupdate'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong"
            ]);
        }
    }

    public function destroy($id)
    {
        $pesan = Pesan::find($id);
        if (!$pesan) {
            return response()->json([
                'message' => "Pesan tidak Ditemukan"
            ], 404);
        }

        //hapus storage
        $storage = Storage::disk('public');

        //hapus gambar
        if ($storage->exists($pesan->foto))
            $storage->delete($pesan->foto);

        //delete pesan
        $pesan->delete();

        return response()->json([
            'message' => "Pesan berhasil di hapus"
        ]);
    }
}
