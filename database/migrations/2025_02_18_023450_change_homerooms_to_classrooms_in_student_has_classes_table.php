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
        Schema::table('student_has_classes', function (Blueprint $table) {
            $table->renameColumn('homerooms_id', 'classrooms_id');
            $table->foreign('classrooms_id')->references('id')->on('classrooms')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_has_classes', function (Blueprint $table) {
            $table->renameColumn('classrooms_id', 'homerooms_id');
            $table->foreign('homerooms_id')->references('id')->on('home_rooms')->onUpdate('cascade')->onDelete('cascade');
        });
    }
};
