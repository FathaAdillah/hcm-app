<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function checkin(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i:s',
            'latlong_in' => 'required|string',
            'employees_id' => 'required|integer',
        ]);

        $data = [
            'date' => $request->date,
            'check_in' => $request->check_in,
            'latlong_in' => $request->latlong_in,
            'employees_id' => $request->employees_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Absensi::create($data);

        return response()->json(['message' => 'Check-in successful']);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'check_out' => 'required|date_format:H:i:s',
            'latlong_out' => 'required|string',
            'employees_id' => 'required|integer',
        ]);

        DB::table('attendance')
            ->where('date', $validated['date'])
            ->where('employees_id', $validated['employees_id'])
            ->update([
                'check_out' => $validated['check_out'],
                'latlong_out' => $validated['latlong_out'],
                'updated_at' => now(),
            ]);

        return response()->json(['message' => 'Check-out successful']);
    }
}
