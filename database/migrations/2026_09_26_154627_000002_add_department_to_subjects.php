<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable("subjects")) {
            return;
        }

        Schema::table("subjects", function (Blueprint $table) {
            if (!Schema::hasColumn("subjects", "department_id")) {
                $table->foreignId("department_id")
                    ->nullable()
                    ->after("school_id")
                    ->constrained("departments")
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable("subjects")) {
            Schema::table("subjects", function (Blueprint $table) {
                if (Schema::hasColumn("subjects", "department_id")) {
                    $table->dropConstrainedForeignId("department_id");
                }
            });
        }
    }
};