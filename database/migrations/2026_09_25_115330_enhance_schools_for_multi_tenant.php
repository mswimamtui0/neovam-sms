<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::table("schools", function (Blueprint $table) {
 if (!Schema::hasColumn("schools","group_name")) {
 $table->string("group_name")->nullable()->after("name");
 }
 if (!Schema::hasColumn("schools","branch_code")) {
 $table->string("branch_code")->nullable()->after("code");
 }
 if (!Schema::hasColumn("schools","subdomain")) {
 $table->string("subdomain")->nullable()->unique()->after("branch_code");
 }
 if (!Schema::hasColumn("schools","is_active")) {
 $table->boolean("is_active")->default(true)->after("has_alevel");
 }
 if (!Schema::hasColumn("schools","parent_school_id")) {
 $table->foreignId("parent_school_id")->nullable()->after("is_active")
 ->constrained("schools")->nullOnDelete();
 }
 if (!Schema::hasColumn("schools","subscription_plan")) {
 $table->string("subscription_plan")->default("standard")->after("parent_school_id");
 }
 if (!Schema::hasColumn("schools","subscription_expires_at")) {
 $table->date("subscription_expires_at")->nullable()->after("subscription_plan");
 }
 if (!Schema::hasColumn("schools","sms_balance_units")) {
 $table->integer("sms_balance_units")->default(0)->after("subscription_expires_at");
 }
 if (!Schema::hasColumn("schools","settings")) {
 $table->text("settings")->nullable()->after("sms_balance_units");
 }
 });
 }

 public function down(): void {
 Schema::table("schools", function (Blueprint $table) {
 $table->dropForeign(["parent_school_id"]);
 $table->dropColumn([
 "group_name","branch_code","subdomain","is_active",
 "parent_school_id","subscription_plan","subscription_expires_at",
 "sms_balance_units","settings",
 ]);
 });
 }
};