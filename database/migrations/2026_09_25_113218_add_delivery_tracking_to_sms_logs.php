<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::table("sms_logs", function (Blueprint $table) {
 $table->string("delivery_status")->default("pending")->after("status");
 // pending | delivered | undelivered | rejected | expired
 $table->timestamp("sent_at")->nullable()->after("delivery_status");
 $table->timestamp("delivered_at")->nullable()->after("sent_at");
 $table->timestamp("failed_at")->nullable()->after("delivered_at");
 $table->string("error_code")->nullable()->after("failed_at");
 $table->text("error_message")->nullable()->after("error_code");
 $table->decimal("cost", 10, 4)->default(0)->after("error_message");
 $table->string("network")->nullable()->after("cost"); // Vodacom, Airtel, etc.
 $table->integer("delivery_attempts")->default(0)->after("network");

 $table->index(["status","delivery_status"]);
 $table->index("sent_at");
 });
 }

 public function down(): void {
 Schema::table("sms_logs", function (Blueprint $table) {
 $table->dropIndex(["status","delivery_status"]);
 $table->dropIndex(["sent_at"]);
 $table->dropColumn([
 "delivery_status","sent_at","delivered_at","failed_at",
 "error_code","error_message","cost","network","delivery_attempts",
 ]);
 });
 }
};