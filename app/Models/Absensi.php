<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'attendance';
    protected $fillable = [
        'date',
        'check_in',
        'check_out',
        'latlong_in',
        'latlong_out',
        'employees_id',
    ];
}
