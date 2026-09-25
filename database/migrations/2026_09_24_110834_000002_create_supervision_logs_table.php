<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("supervision_logs", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->date("log_date");
 $table->string("area"); // assembly, break, lunch, evening, night, gate, exam
 $table->string("status")->default("done"); // done, missed, issue
 $table->text("notes")->nullable();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("supervision_logs"); }
};