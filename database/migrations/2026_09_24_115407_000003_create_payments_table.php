<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("payments", function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id")->constrained()->cascadeOnDelete();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("recorded_by")->nullable()->constrained("users")->nullOnDelete();
            $table->string("receipt_no")->unique();
            $table->decimal("amount", 12, 2);
            $table->string("method")->default("cash"); // cash, bank, mobile_money, cheque
            $table->string("reference")->nullable();
            $table->date("payment_date");
            $table->text("notes")->nullable();
            $table->boolean("sms_sent")->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("payments"); }
};