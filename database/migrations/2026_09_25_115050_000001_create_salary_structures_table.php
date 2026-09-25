<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("salary_structures", function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->decimal("basic_salary", 12, 2)->default(0);
            $table->decimal("allowance_house", 12, 2)->default(0);
            $table->decimal("allowance_transport", 12, 2)->default(0);
            $table->decimal("allowance_meal", 12, 2)->default(0);
            $table->decimal("allowance_other", 12, 2)->default(0);
            $table->decimal("deduction_tax", 12, 2)->default(0);
            $table->decimal("deduction_nssf", 12, 2)->default(0);
            $table->decimal("deduction_loan", 12, 2)->default(0);
            $table->decimal("deduction_other", 12, 2)->default(0);
            $table->string("bank_name")->nullable();
            $table->string("bank_account")->nullable();
            $table->string("payment_method")->default("bank");
            $table->date("effective_from");
            $table->boolean("is_active")->default(true);
            $table->text("notes")->nullable();
            $table->timestamps();

            $table->index(["staff_id","is_active"]);
        });
    }
    public function down(): void { Schema::dropIfExists("salary_structures"); }
};