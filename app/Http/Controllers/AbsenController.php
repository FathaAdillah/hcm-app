<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Log;

class AbsenController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $employeeId = 1; // Assuming employee ID is 1 for now, replace with actual employee ID

        // Ambil data absensi hari ini untuk pengguna
        $absensiToday = Absensi::where('date', $today)
            ->where('employees_id', $employeeId)
            ->first();

        // Tentukan status check-in dan check-out
        $hasCheckedIn = $absensiToday && $absensiToday->check_in ? true : false;
        $hasCheckedOut = $absensiToday && $absensiToday->check_out ? true : false;

        $absensis = Absensi::all(); // Ambil semua data absensi dari database

        return view('user.absen', compact('absensis', 'hasCheckedIn', 'hasCheckedOut'));
    }

    public function checkin(Request $request)
    {
        Log::info('Check-in Request:', $request->all());

        $validated = $request->validate([
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i:s',
            'latlong_in' => 'required|string',
            'employees_id' => 'required|integer',
        ]);

        // Periksa apakah user sudah check-in pada hari yang sama
        $existingCheckin = Absensi::where('date', $validated['date'])
            ->where('employees_id', $validated['employees_id'])
            ->whereNotNull('check_in')
            ->first();

        if ($existingCheckin) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Tentukan status
        $checkInTime = strtotime($validated['check_in']);
        $onTime = strtotime('08:00:00');
        $status = $checkInTime > $onTime ? 'Terlambat' : 'On Time';

        $data = [
            'date' => $validated['date'],
            'check_in' => $validated['check_in'],
            'latlong_in' => $validated['latlong_in'],
            'employees_id' => $validated['employees_id'],
            'status' => $status, // Set status
            'check_out' => null,
            'latlong_out' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Absensi::create($data);
        return redirect()->back()->with('success', 'Check-in berhasil');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'check_out' => 'required|date_format:H:i:s',
            'latlong_out' => 'required|string',
            'employees_id' => 'required|integer',
        ]);

        // Periksa apakah user sudah check-out pada hari yang sama
        $existingCheckout = Absensi::where('date', $validated['date'])
            ->where('employees_id', $validated['employees_id'])
            ->whereNotNull('check_out')
            ->first();

        if ($existingCheckout) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // Periksa apakah waktu check-out sudah lewat jam 17.00
        $checkOutTime = strtotime($validated['check_out']);
        $allowedTime = strtotime('17:00:00');
        if ($checkOutTime < $allowedTime) {
            return redirect()->back()->with('error', 'Anda hanya bisa check-out setelah jam 17:00.');
        }

        Absensi::where('date', $validated['date'])
            ->where('employees_id', $validated['employees_id'])
            ->update([
                'check_out' => $validated['check_out'],
                'latlong_out' => $validated['latlong_out'],
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Check-out berhasil');
    }
}
