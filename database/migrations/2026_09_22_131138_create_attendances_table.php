<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
 Schema::create('attendances', function (Blueprint $table) {
 $table->id();
 $table->foreignId('student_id')->constrained()->cascadeOnDelete();
 $table->foreignId('classroom_id')->nullable()->constrained()->nullOnDelete();
 $table->date('date');
 $table->string('status');
 $table->string('recorded_by')->nullable();
 $table->boolean('sms_sent')->default(false);
 $table->timestamps();

 $table->unique(['student_id', 'date']);
 });
 }

 public function down(): void
 {
 Schema::dropIfExists('attendances');
 }
};
