<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("sms_pricing_rules", function (Blueprint $table) {
            $table->id();
            $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->decimal("unit_price", 10, 4)->default(0);   // price per SMS unit
            $table->string("currency", 10)->default("TZS");
            $table->date("effective_from");
            $table->date("effective_to")->nullable();
            $table->boolean("is_active")->default(true);
            $table->text("notes")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("sms_pricing_rules"); }
};