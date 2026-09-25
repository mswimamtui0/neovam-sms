<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::table("classrooms", function (Blueprint $table) {
 $table->foreignId("class_teacher_id")->nullable()->after("school_id")
 ->constrained("staff")->nullOnDelete();
 });
 }
 public function down(): void {
 Schema::table("classrooms", function (Blueprint $table) {
 $table->dropConstrainedForeignId("class_teacher_id");
 });
 }
};