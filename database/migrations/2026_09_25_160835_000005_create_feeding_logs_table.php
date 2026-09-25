<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("feeding_logs", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("meal"); // breakfast, lunch, dinner, snack
 $table->string("menu")->nullable();
 $table->integer("students_served")->default(0);
 $table->string("hygiene_rating")->nullable(); // excellent, good, fair, poor
 $table->text("notes")->nullable();
 $table->date("log_date");
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("feeding_logs"); }
};