<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("exam_timetables", function (Blueprint $table) {
 $table->id();
 $table->foreignId("exam_id")->constrained()->cascadeOnDelete();
 $table->foreignId("subject_id")->constrained()->cascadeOnDelete();
 $table->date("exam_date");
 $table->time("start_time");
 $table->time("end_time");
 $table->string("venue")->nullable();
 $table->string("invigilator")->nullable();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("exam_timetables"); }
};