<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
 Schema::create('incidents', function (Blueprint $table) {
 $table->id();
 $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
 $table->string('type');
 $table->text('description');
 $table->string('reported_by')->nullable();
 $table->boolean('sms_sent')->default(false);
 $table->timestamps();
 });
 }

 public function down(): void
 {
 Schema::dropIfExists('incidents');
 }
};
