<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("staff", function (Blueprint $table) {
            if (!Schema::hasColumn("staff", "staff_category")) {
                $table->string("staff_category")->default("non_teaching")->after("staff_type");
            }
            if (!Schema::hasColumn("staff", "primary_department_id")) {
                $table->foreignId("primary_department_id")->nullable()->after("staff_category")
                      ->constrained("departments")->nullOnDelete();
            }
            if (!Schema::hasColumn("staff", "extra_roles")) {
                $table->string("extra_roles")->nullable()->after("department_roles");
            }
        });
    }

    public function down(): void
    {
        Schema::table("staff", function (Blueprint $table) {
            if (Schema::hasColumn("staff", "primary_department_id")) {
                $table->dropConstrainedForeignId("primary_department_id");
            }
            $table->dropColumn(["staff_category", "extra_roles"]);
        });
    }
};