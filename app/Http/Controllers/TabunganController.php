<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tabungan;

class TabunganController extends Controller
{
    // public function store(Request $request)
    // {
    //     $checTab = Tabungan::Where('users_id', $request->users_id)->first();
    //     if ()
    // }

    public function index (){
        return Tabungan::all();
    }
}
