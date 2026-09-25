<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
 Schema::create('results', function (Blueprint $table) {
 $table->id();
 $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
 $table->foreignId('student_id')->constrained()->cascadeOnDelete();
 $table->string('subject');
 $table->integer('marks');
 $table->string('grade')->nullable();
 $table->boolean('sms_sent')->default(false);
 $table->timestamps();
 });
 }

 public function down(): void
 {
 Schema::dropIfExists('results');
 }
};
