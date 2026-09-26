<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable("departments")) {
            return;
        }

        Schema::create("departments", function (Blueprint $table) {
            $table->id();
            $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
            $table->string("name")->unique();
            $table->string("code")->unique();
            $table->string("type")->default("non_teaching"); // teaching | non_teaching
            $table->string("color")->default("blue");
            $table->text("description")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("departments");
    }
};