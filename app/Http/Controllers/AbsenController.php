<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function index()
    {
        return view('user.absen');
    }


}
