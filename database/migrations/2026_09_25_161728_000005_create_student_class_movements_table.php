<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("student_class_movements", function (Blueprint $table) {
 $table->id();
 $table->foreignId("student_id")->constrained()->cascadeOnDelete();
 $table->foreignId("from_classroom_id")->nullable()->constrained("classrooms")->nullOnDelete();
 $table->foreignId("to_classroom_id")->nullable()->constrained("classrooms")->nullOnDelete();
 $table->string("from_level")->nullable();
 $table->string("to_level")->nullable();
 $table->string("movement_type"); // promotion, transfer, stream_change, repeat, admitted, left
 $table->text("reason")->nullable();
 $table->date("effective_date");
 $table->boolean("sms_sent")->default(false);
 $table->foreignId("moved_by")->nullable()->constrained("users")->nullOnDelete();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("student_class_movements"); }
};