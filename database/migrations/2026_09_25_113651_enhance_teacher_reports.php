<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::table("teacher_reports", function (Blueprint $table) {
 if (!Schema::hasColumn("teacher_reports","review_status")) {
 $table->string("review_status")->default("pending")->after("status");
 // pending | under_review | approved | rejected | needs_revision
 }
 if (!Schema::hasColumn("teacher_reports","reviewed_by")) {
 $table->foreignId("reviewed_by")->nullable()->after("review_status")
 ->constrained("users")->nullOnDelete();
 }
 if (!Schema::hasColumn("teacher_reports","reviewed_at")) {
 $table->timestamp("reviewed_at")->nullable()->after("reviewed_by");
 }
 if (!Schema::hasColumn("teacher_reports","head_comment")) {
 $table->text("head_comment")->nullable()->after("reviewed_at");
 }
 if (!Schema::hasColumn("teacher_reports","rating")) {
 $table->integer("rating")->nullable()->after("head_comment");
 // 1 to 5
 }
 if (!Schema::hasColumn("teacher_reports","periods_taught")) {
 $table->integer("periods_taught")->default(0)->after("rating");
 }
 if (!Schema::hasColumn("teacher_reports","students_absent")) {
 $table->integer("students_absent")->default(0)->after("periods_taught");
 }
 });
 }

 public function down(): void {
 Schema::table("teacher_reports", function (Blueprint $table) {
 $table->dropForeign(["reviewed_by"]);
 $table->dropColumn([
 "review_status","reviewed_by","reviewed_at","head_comment",
 "rating","periods_taught","students_absent",
 ]);
 });
 }
};