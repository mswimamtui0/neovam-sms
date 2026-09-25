<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Drop existing sms_templates table and recreate with composite unique
        Schema::dropIfExists("sms_templates");

        Schema::create("sms_templates", function (Blueprint $table) {
            $table->id();
            $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
            $table->string("key");
            $table->string("name");
            $table->string("language", 5)->default("en");
            $table->text("body");
            $table->text("description")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();

            // Composite unique — key + language
            $table->unique(["key","language"], "unique_template_key_lang");
        });
    }

    public function down(): void {
        Schema::dropIfExists("sms_templates");
    }
};