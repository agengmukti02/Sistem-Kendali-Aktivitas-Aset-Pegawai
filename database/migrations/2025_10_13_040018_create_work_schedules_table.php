<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('work_date');
            $table->enum('shift', ['Pagi', 'Siang', 'Malam'])->nullable();
            $table->enum('status', [
                'Hadir', 
                'Dinas Dalam', 
                'Dinas Luar', 
                'Cuti', 
                'Izin', 
                'Sakit', 
                'Alpha',
                'Libur'
            ])->default('Hadir');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['employee_id', 'work_date']);
            $table->index('work_date');
            $table->index('status');
            
            // Unique constraint to prevent duplicate schedules
            $table->unique(['employee_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
