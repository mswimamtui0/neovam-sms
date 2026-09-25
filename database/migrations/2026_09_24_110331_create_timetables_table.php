<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("timetables", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->foreignId("classroom_id")->constrained()->cascadeOnDelete();
 $table->foreignId("subject_id")->nullable()->constrained()->nullOnDelete();
 $table->string("day_of_week"); // Monday..Sunday
 $table->time("start_time");
 $table->time("end_time");
 $table->string("period_label")->nullable(); // Period 1, 2...
 $table->string("room")->nullable();
 $table->text("notes")->nullable();
 $table->timestamps();

 $table->index(["staff_id","day_of_week"]);
 $table->index(["classroom_id","day_of_week"]);
 });
 }
 public function down(): void { Schema::dropIfExists("timetables"); }
};