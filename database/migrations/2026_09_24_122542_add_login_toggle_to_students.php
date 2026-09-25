<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::table("students", function (Blueprint $table) {
 $table->boolean("can_login")->default(false)->after("user_id");
 $table->timestamp("login_enabled_at")->nullable()->after("can_login");
 $table->string("login_enabled_by")->nullable()->after("login_enabled_at");
 });
 }

 public function down(): void {
 Schema::table("students", function (Blueprint $table) {
 $table->dropColumn(["can_login","login_enabled_at","login_enabled_by"]);
 });
 }
};