<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("payroll_runs", function (Blueprint $table) {
            $table->id();
            $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
            $table->string("period");     // e.g. 2026-09
            $table->string("label");
            $table->date("period_start");
            $table->date("period_end");
            $table->integer("staff_count")->default(0);
            $table->decimal("total_gross", 14, 2)->default(0);
            $table->decimal("total_deductions", 14, 2)->default(0);
            $table->decimal("total_net", 14, 2)->default(0);
            $table->string("status")->default("draft"); // draft | approved | paid | cancelled
            $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete();
            $table->foreignId("approved_by")->nullable()->constrained("users")->nullOnDelete();
            $table->timestamp("approved_at")->nullable();
            $table->timestamp("paid_at")->nullable();
            $table->text("notes")->nullable();
            $table->timestamps();

            $table->unique(["school_id","period"], "unique_payroll_period");
        });
    }
    public function down(): void { Schema::dropIfExists("payroll_runs"); }
};