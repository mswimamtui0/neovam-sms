<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("fee_reminders", function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id")->constrained()->cascadeOnDelete();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->string("reminder_type"); // before_due | on_due | after_due | overdue_final
            $table->date("due_date");
            $table->decimal("balance", 12, 2);
            $table->boolean("sms_sent")->default(false);
            $table->timestamp("sms_sent_at")->nullable();
            $table->string("sms_status")->nullable();
            $table->text("notes")->nullable();
            $table->timestamps();

            $table->index(["invoice_id","reminder_type"]);
            $table->index("sms_sent");
        });
    }
    public function down(): void { Schema::dropIfExists("fee_reminders"); }
};