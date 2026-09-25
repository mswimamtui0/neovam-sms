<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("sms_balance_transactions", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
 $table->string("type"); // topup | debit | refund | adjustment
 $table->integer("units"); // + for topup, - for debit
 $table->decimal("amount", 12, 2)->default(0); // money moved
 $table->string("currency", 10)->default("TZS");
 $table->string("reference")->nullable(); // external ref / invoice no
 $table->text("description")->nullable();
 $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete();
 $table->timestamps();

 $table->index(["type","created_at"]);
 });
 }
 public function down(): void { Schema::dropIfExists("sms_balance_transactions"); }
};