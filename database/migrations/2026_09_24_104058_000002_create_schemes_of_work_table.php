<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("schemes_of_work", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->foreignId("classroom_id")->nullable()->constrained()->nullOnDelete();
 $table->foreignId("subject_id")->nullable()->constrained()->nullOnDelete();
 $table->string("term");
 $table->integer("year");
 $table->text("content");
 $table->string("status")->default("draft");
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("schemes_of_work"); }
};