<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("staff_transfers", function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->string("transfer_type"); // transfer_in | transfer_out | internal_move | promotion | demotion
            $table->string("from_position")->nullable();
            $table->string("to_position")->nullable();
            $table->string("from_department")->nullable();
            $table->string("to_department")->nullable();
            $table->string("from_school")->nullable();
            $table->string("to_school")->nullable();
            $table->date("effective_date");
            $table->text("reason")->nullable();
            $table->text("notes")->nullable();
            $table->string("status")->default("pending"); // pending | approved | rejected | completed
            $table->foreignId("requested_by")->nullable()->constrained("users")->nullOnDelete();
            $table->foreignId("approved_by")->nullable()->constrained("users")->nullOnDelete();
            $table->timestamp("approved_at")->nullable();
            $table->text("approval_notes")->nullable();
            $table->timestamps();

            $table->index(["staff_id","effective_date"]);
            $table->index("status");
        });
    }
    public function down(): void { Schema::dropIfExists("staff_transfers"); }
};