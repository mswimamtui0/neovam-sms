<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("department_activities", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
 $table->string("department"); // Academic, Discipline, Sports, Health, Boarding, etc.
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("title");
 $table->text("description");
 $table->string("activity_type")->default("general");
 // task | meeting | event | inspection | report | other
 $table->date("activity_date");
 $table->string("status")->default("pending");
 // pending | in_progress | completed | cancelled
 $table->string("priority")->default("normal");
 // low | normal | high | urgent
 $table->text("outcome")->nullable();
 $table->text("notes")->nullable();
 $table->foreignId("approved_by")->nullable()->constrained("users")->nullOnDelete();
 $table->timestamp("approved_at")->nullable();
 $table->timestamps();

 $table->index(["department","activity_date"]);
 $table->index("status");
 });
 }
 public function down(): void { Schema::dropIfExists("department_activities"); }
};