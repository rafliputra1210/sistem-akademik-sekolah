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
        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['schedule_id', 'student_id', 'date'], 'attendances_schedule_student_date_unique');
            $table->index('date', 'attendances_date_index');
        });

        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->unique(['teacher_id', 'date'], 'teacher_attendances_teacher_date_unique');
            $table->index('date', 'teacher_attendances_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_schedule_student_date_unique');
            $table->dropIndex('attendances_date_index');
        });

        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->dropUnique('teacher_attendances_teacher_date_unique');
            $table->dropIndex('teacher_attendances_date_index');
        });
    }
};
