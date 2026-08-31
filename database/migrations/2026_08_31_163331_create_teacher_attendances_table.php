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
    Schema::create('teacher_attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
        $table->date('date');
        $table->time('clock_in')->nullable(); // Waktu masuk dari server
        $table->string('photo_in')->nullable(); // Path gambar base64/file
        $table->time('clock_out')->nullable(); // Waktu pulang
        $table->string('photo_out')->nullable(); 
        $table->enum('status', ['Hadir', 'Terlambat', 'Alpha'])->default('Hadir');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
