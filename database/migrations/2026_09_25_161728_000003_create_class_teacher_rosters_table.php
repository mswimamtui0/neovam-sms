<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("class_teacher_rosters", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
 $table->foreignId("classroom_id")->constrained()->cascadeOnDelete();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->integer("year");
 $table->string("term")->nullable();
 $table->date("start_date");
 $table->date("end_date")->nullable();
 $table->boolean("is_active")->default(true);
 $table->text("notes")->nullable();
 $table->foreignId("assigned_by")->nullable()->constrained("users")->nullOnDelete();
 $table->timestamps();

 $table->index(["classroom_id","year"]);
 });
 }
 public function down(): void { Schema::dropIfExists("class_teacher_rosters"); }
};