<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("sms_templates", function (Blueprint $table) {
            $table->id();
            $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
            $table->string("key")->unique();       // absence, result, payment, welcome, emergency, promotion, fee_reminder, etc.
            $table->string("name");
            $table->string("language", 5)->default("en");  // en | sw
            $table->text("body");
            $table->text("description")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("sms_templates"); }
};