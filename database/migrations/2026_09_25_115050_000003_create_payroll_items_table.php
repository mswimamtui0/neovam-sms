<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("payroll_items", function (Blueprint $table) {
            $table->id();
            $table->foreignId("payroll_run_id")->constrained()->cascadeOnDelete();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->decimal("basic_salary", 12, 2)->default(0);
            $table->decimal("allowances", 12, 2)->default(0);
            $table->decimal("deductions", 12, 2)->default(0);
            $table->decimal("gross_pay", 12, 2)->default(0);
            $table->decimal("net_pay", 12, 2)->default(0);
            $table->decimal("amount_paid", 12, 2)->default(0);
            $table->string("status")->default("pending"); // pending | paid
            $table->timestamp("paid_at")->nullable();
            $table->string("payment_reference")->nullable();
            $table->text("notes")->nullable();
            $table->timestamps();

            $table->unique(["payroll_run_id","staff_id"], "unique_staff_payroll");
        });
    }
    public function down(): void { Schema::dropIfExists("payroll_items"); }
};