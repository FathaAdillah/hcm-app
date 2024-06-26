<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id()->primary()->autoIncrement();
            $table->string('name');
            $table->time('time_in');
            $table->time('time_out');
            $table->string('is_active')->default('1');
            $table->string('is_delete')->default('0');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
