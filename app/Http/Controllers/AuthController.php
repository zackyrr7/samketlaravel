<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class AuthController extends Controller
{

    public function index()
    {
        return User::all();
    }

    public function indexUser($id)
    {
        return User::find($id);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'no_hp' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'succes' => False,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = 'Perlu Verifikasi';
        $user = User::create($input);



        $succes['token'] = $user->createToken('auth_token')->plainTextToken;
        $succes['name'] = $user->name;
        $succes['id'] = $user->id;
        $succes['role'] = $user->role;

        $tabungan  = new Tabungan();

        $tabungan->users_id = $succes['id'];
        $tabungan->saldo = 0;
        $tabungan->status = 'Akun Dibuat';
        $tabungan->tanggal = Carbon::now();
        $tabungan->save();

        return response()->json([
            'succes' => true,
            'message' => 'Sukses mendaftar',
            'data' => $succes

        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            // $succes['token']= $auth->createToken('auth_token')->plainTextToken;
            // $succes['name'] = $auth->name;
            // $succes['role'] = $auth->role;
            // $succes['id'] = $auth->id;

            $nama = $auth->name;
            $no_hp = $auth->no_hp;
            $id = $auth->id;
            $token = $auth->createToken('auth_token')->plainTextToken;


            return response()->json([
                'succes' => true,
                'message' => 'Sukses login',
                'nama' => $nama,
                'id' => $id,
                'token' => $token,
                'no_hp' => $no_hp

            ]);
        } else {
            return response()->json([
                'succes' => False,
                'message' => 'Email atau Password salah',
                'data' => null
            ]);
        }
    }

    public function changeRole(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $auth->role = $request->role;
            $auth->save();
            $succes['token'] = $auth->createToken('auth_token')->plainTextToken;
            $succes['name'] = $auth->name;
            $succes['id'] = $auth->id;

            return response()->json([
                'succes' => true,
                'message' => 'Sukses login',
                'data' => $succes
            ]);
        } else {
            return response()->json([
                'succes' => False,
                'message' => 'Email atau Password salah',
                'data' => null
            ]);
        }
    }

    public function verifikasi(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {

            return response()->json([
                'succes' => False,
                'message' => 'User tidak ada',
                'data' => null
            ]);

            
        } else {
            $user->role = "user";
            $user->save();
            $succes['token'] = $user->createToken('auth_token')->plainTextToken;
            $succes['name'] = $user->name;
            $succes['id'] = $user->id;

            return response()->json([
                'succes' => true,
                'message' => 'Sukses login',
                'role' => $user->role,
                'data' => $succes
            ]);
        }
    }
}
