<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
 Schema::create('sms_logs', function (Blueprint $table) {
 $table->id();
 $table->string('recipient');
 $table->text('message');
 $table->string('trigger');
 $table->string('status')->default('pending');
 $table->string('gateway_ref')->nullable();
 $table->timestamps();
 });
 }

 public function down(): void
 {
 Schema::dropIfExists('sms_logs');
 }
};
