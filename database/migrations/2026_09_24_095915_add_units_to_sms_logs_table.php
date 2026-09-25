<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
 Schema::table("sms_logs", function (Blueprint $table) {
 $table->integer("units")->default(1)->after("message");
 $table->integer("char_count")->default(0)->after("units");
 });
 }

 public function down(): void
 {
 Schema::table("sms_logs", function (Blueprint $table) {
 $table->dropColumn(["units", "char_count"]);
 });
 }
};