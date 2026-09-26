<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable("departments")) {
            return;
        }

        Schema::table("departments", function (Blueprint $table) {
            if (!Schema::hasColumn("departments", "type")) {
                $table->string("type")->default("non_teaching")->after("name");
            }
            if (!Schema::hasColumn("departments", "code")) {
                $table->string("code")->nullable()->after("name");
            }
            if (!Schema::hasColumn("departments", "color")) {
                $table->string("color")->default("blue")->after("type");
            }
            if (!Schema::hasColumn("departments", "description")) {
                $table->text("description")->nullable()->after("color");
            }
            if (!Schema::hasColumn("departments", "is_active")) {
                $table->boolean("is_active")->default(true)->after("description");
            }
            if (!Schema::hasColumn("departments", "school_id")) {
                $table->foreignId("school_id")->nullable()->after("id");
            }
        });
    }

    public function down(): void
    {
        // no-op — we don't drop columns the app may be using
    }
};