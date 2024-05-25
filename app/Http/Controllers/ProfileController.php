<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class ProfileController extends Controller
{

    public function index()
    {
        return view('pages.profile.show');
    }

    public function updateEmployee(Request $request, $id)
    {

    }
}
