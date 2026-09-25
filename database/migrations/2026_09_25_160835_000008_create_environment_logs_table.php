<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
 Schema::create("environment_logs", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("log_type");
 $table->string("area")->nullable();
 $table->string("rating")->nullable();
 $table->text("description");
 $table->text("action_taken")->nullable();
 $table->date("log_date");
 $table->timestamps();
 });
 }

 public function down(): void
 {
 Schema::dropIfExists("environment_logs");
 }
};